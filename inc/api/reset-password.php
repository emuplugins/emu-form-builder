<?php

//  =================================
//  ENDPOINT PARA REDEFINIR SENHA
// ==================================

add_action('rest_api_init', function() {

    register_rest_route('emu_plugins/v1', 'reset-password/', [
        'methods' => 'POST', 
        'callback' => 'efbResetPassword',
        'permission_callback' => '__return_true', 
    ]);

});

function efbGetUserIp() {

    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        // Cloudflare
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // Proxy transparente
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Proxy ou balanceador de carga
        $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ip_list[0]); // Primeiro IP da lista
    } else {
        // IP direto do usuário
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : 'IP inválido';
}

function findUserBykey($reset_key) {
    // Procurar usuários pelo código de confirmação (reset_password_key)
    $users = get_users([
        'meta_key' => 'reset_password_key', // Chave do meta dado
        'meta_value' => $reset_key,         // Valor do código que você está procurando
        'number' => 1,                      // Limitar a busca para um único usuário (você pode alterar conforme necessário)
        'fields' => ['ID', 'user_login', 'user_email'], // Campos a serem retornados
    ]);

    if (!empty($users)) {
        // Se encontrado, retorna o primeiro usuário encontrado
        return $users[0];
    } else {
        // Caso contrário, retorna false ou um erro
        return false;
    }
}

function efbResetPassword(WP_REST_Request $request) {

    $user_ip = efbGetUserIp();
    $email = sanitize_email($request->get_param('email')) ?? '';
    $reset_key = wp_slash($request->get_param('resetKey')) ?? '';
    $password = wp_slash($request->get_param('password')) ?? '';
    $user_id = findUserBykey($reset_key);
    $user_id = $user_id ? $user_id->ID : null;

    // Validar se os parâmetros básicos foram enviados
    if (!$email && !$reset_key) {
        return new WP_REST_Response(['errors' => 'Invalid email'], 200);
    }

    // Caso o usuário esteja solicitando um código
    if ($email) {
        $user_data = get_user_by('email', $email);

        if (!$user_data) {
            return new WP_REST_Response(['errors' => 'Email not found'], 200);
        }

        // Verificar tentativas de envio
        $reset_attempts = get_user_meta($user_data->ID, 'reset_attempts', true) ?: 0;
        $last_reset_time = get_user_meta($user_data->ID, 'last_reset_attempt_time', true);

        if ($reset_attempts >= 3 && (time() - $last_reset_time) < 900) { // 900 segundos = 15 minutos
            return new WP_REST_Response(['errors' => 'Too many reset attempts. Try again in 15 minutes.'], 429);
        }

        // Resetar contador após 15 minutos
        if ((time() - $last_reset_time) >= 900) {
            update_user_meta($user_data->ID, 'reset_attempts', 0);
        }

        // Gerar código de redefinição
        $reset_key = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);

        update_user_meta($user_data->ID, 'reset_password_key', $reset_key);
        update_user_meta($user_data->ID, 'reset_password_expiration', time() + 1800); // 30 minutos

        // Atualizar contador de tentativas
        update_user_meta($user_data->ID, 'reset_attempts', $reset_attempts + 1);
        update_user_meta($user_data->ID, 'last_reset_attempt_time', time());

        wp_mail($user_data->user_email, 'Redefinição de senha', 'Cole este código no site: ' . $reset_key);
        return new WP_REST_Response(['ok' => 1], 200);
    }

    if (!$reset_key){
        return new WP_REST_Response(['errors' => 'Código de redefinição inválido.'], 200);
    }

    // Verificar se o código é válido
    $stored_key = get_user_meta($user_id, 'reset_password_key', true);
    $expiration = get_user_meta($user_id, 'reset_password_expiration', true);

    if ($stored_key === $reset_key && time() < $expiration) {
        if (strlen($password) < 8) {
            return new WP_REST_Response(['errors' => 'Senha fraca, insira pelo menos 8 letras ou números.'], 400);
        }

        wp_set_password($password, $user_id);

        delete_user_meta($user_id, 'reset_password_key');
        delete_user_meta($user_id, 'reset_password_expiration');
        return new WP_REST_Response(['ok' => 1], 200);
    } else {
        return new WP_REST_Response(['errors' => 'Reset key invalid'], 200);
    }
}