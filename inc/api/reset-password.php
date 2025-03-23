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

    $email = sanitize_email($request->get_param('email')) ?? '';
    $reset_key = wp_slash($request->get_param('resetKey')) ?? '';
    $password = wp_slash($request->get_param('password')) ?? '';
    $user_id = findUserBykey($reset_key);

    // não é possivel enviar o e-mail e ao mesmo tempo não enviar o reset_key. Se quiser enviar o e-mail, é obrigatório o reset_key
    if(!$email && !$reset_key){
        return new WP_REST_Response(['errors' => 'invalid email'], 200);
    }

    // Caso tenham sido enviados, trata do envio do código, mas não faz os outros métodos abaixo desse
    if($email){

        $user_data = get_user_by( 'email', $email );
        $reset_key = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT); // Gerando número aleatório de 5 dígitos

        if (!$user_data) {
            return new WP_REST_Response(['errors' => 'email not found'], 200);
        }

        update_user_meta( $user_data->ID, 'reset_password_key', $reset_key );
        update_user_meta( $user_data->ID, 'reset_password_expiration', time() + 1800 ); // 30 minutos

        
        wp_mail( $user_data->user_email, 'Redefinição de senha', 'Cole este código no site:' . $reset_key );
        return new WP_REST_Response(['ok' => 1], 200);
    }

    // Começa a redefinir a senha, caso não tenha sido enviado o e-mail
    $stored_key = get_user_meta( $user_id, 'reset_password_key', true );
    $expiration = get_user_meta( $user_id, 'reset_password_expiration', true );

    // se a senha estiver correta, e não tiver expirado o tempo retorna ok, do contrário retorna erro
    if ( $stored_key === $reset_key && time() < $expiration ) {

        wp_set_password( $password, $user_id );

        delete_user_meta( $user_id, 'reset_password_key' );
        delete_user_meta( $user_id, 'reset_password_expiration' );
        return new WP_REST_Response(['ok' => 1], 200);

    } else {
        delete_user_meta( $user_id, 'reset_password_key' );
        delete_user_meta( $user_id, 'reset_password_expiration' );
        return new WP_REST_Response(['errors' => 'reset_key invalid'], 200);
    }

}
