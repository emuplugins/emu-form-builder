<?php

// acho que não precisa verificar por que é um endpoint de rest api do wp, mas deixa assim
if ( ! defined('ABSPATH')) return;

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
    $email = $request->get_param('email');
	$confirm = $request->get_param('confirm');
	
    // Validação dos campos
    // Dados a serem validados
    $data = [
        'username' => $username,
        'password' => $password,
        'email' => $email
    ];
    
    $validation_errors = $auth->ValidateFields($data);

    if (!empty($validation_errors)) {
        // Retorna os erros de validação se existirem
        return new WP_REST_Response(['errors' => $validation_errors], 200);
    }

    // Se não houver erros de validação, cria o usuário
    $user_id = wp_create_user($username, $password, $email);

    $first_name = ucfirst($username);

    if (!is_wp_error($user_id)) {

        // Atualiza o nome do usuário
        wp_update_user([
            'ID' => $user_id,
            'first_name' => $first_name, // Defina o nome
        ]);
		
		update_user_meta($user_id, 'privacy_policy', $confirm);
    
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
