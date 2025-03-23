<?php

//  =================================
//  ENDPOINT PARA CRIAR O USUÁRIO
// ==================================

add_action('rest_api_init', function() {

    register_rest_route('emu_plugins/v1', 'register/', [
        'methods' => 'POST',
        'callback' => 'efbRegister',
        'permission_callback' => '__return_true', 
    ]);

});

function efbRegister(WP_REST_Request $request) {

    $auth = new EmuAuthenticate;
    
    // Recaptcha
    $recaptchaResponse = wp_slash($request->get_param('g-recaptcha-response') ?? '');
    $recaptchaResponse = $auth->recaptchaVerify($recaptchaResponse);

    if(!$recaptchaResponse){
        return new WP_REST_Response(['error' => 'recaptcha invalid'], 200);
    }

    // Recuperando os dados da requisição
    $username = sanitize_text_field($request->get_param('username'));
    $password = wp_slash($request->get_param('password'));
    $email = sanitize_email($request->get_param('email'));

    // Dados a serem validados
    $data = [
        'username' => $username,
        'password' => $password,
        'email' => $email
    ];

    // Validação dos campos
    $validation_errors = ValidateFields($data);

    if (!empty($validation_errors)) {
        // Retorna os erros de validação se existirem
        return new WP_REST_Response(['errors' => $validation_errors], 200);
    }

    // Se não houver erros de validação, cria o usuário
    $user_id = wp_create_user($username, $password, $email);

    if (!is_wp_error($user_id)) {
        $user = new WP_User($user_id);
        $user->set_role('subscriber');
        wp_set_auth_cookie($user_id, true);
        return new WP_REST_Response(['ok' => 1], 200);
    } else {
        // Se houver erro na criação do usuário, retorna a mensagem de erro
        $error = $user_id->get_error_message();
        return new WP_REST_Response(['errors' => $error], 200);
    }
}

function ValidateFields($data) {

    $errors = [];

    // Validação de nome de usuário
    if (get_user_by('login', $data['username'])) {
        $errors['username'] = 'exists'; // O nome de usuário já existe
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $data['username'])) {
        $errors['username'] = 'invalid_chars'; // Nome de usuário com caracteres inválidos
    }

    // Validação de e-mail
    if (strpos($data['email'], '@') === false || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'invalid'; // E-mail inválido
    }

    // Validação de senha (mínimo de 7 caracteres)
    if (strlen($data['password']) < 7) {
        $errors['password'] = 'too_short'; // Senha muito curta
    }

    return $errors; // Retorna o array de erros, caso existam
}