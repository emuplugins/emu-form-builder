<?php

// acho que não precisa verificar por que é um endpoint de rest api do wp, mas deixa assim
if ( ! defined('ABSPATH')) return;

//  =================================
//  ENDPOINT PARA REALIZAR O LOIGN
// ==================================

add_action('rest_api_init', function() {

    register_rest_route('emu_plugins/v1', 'login/', [
        'methods' => 'POST', 
        'callback' => 'efbLogin',
        'permission_callback' => '__return_true', 
    ]);

});

function efbLogin(WP_REST_Request $request) {

    $auth = new EmuAuthenticate;
    
    // Recaptcha
    $recaptchaResponse = wp_slash($request->get_param('g-recaptcha-response') ?? '');
    $recaptchaResponse = $auth->recaptchaVerify($recaptchaResponse);

    if(!$recaptchaResponse){
        return new WP_REST_Response(['error' => 'Recaptcha inválido'], 200);
    }
    
    // limpando os dados...
    $username = sanitize_text_field($request->get_param('username'));
    $password = wp_slash($request->get_param('password'));

    // verificando se o login está correto

    $user = wp_authenticate($username, $password);	

    if (is_wp_error($user)) {
        return new WP_REST_Response(['error' => 'Usuário ou senha inválidos'], 200);
    }

	// definindo o cookie que autentica o usuário
    // por incrivel que pareça, isso funciona com fetch! 😱

	wp_set_auth_cookie($user->ID, true);

	$user_info = get_userdata($user->id);

	return new WP_REST_Response(['ok' => 1, 'name' => $user_info->display_name], 200);
}