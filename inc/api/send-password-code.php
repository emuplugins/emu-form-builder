<?php

// acho que não precisa verificar por que é um endpoint de rest api do wp, mas deixa assim
if ( ! defined('ABSPATH')) return;

//  =================================
//  ENDPOINT PARA REDEFINIR SENHA
// ==================================

add_action('rest_api_init', function() {

    register_rest_route('emu_plugins/v1', 'send-password-code', [
        'methods' => 'POST', 
        'callback' => 'efbSendPasswordCode',
        'permission_callback' => '__return_true', 
    ]);

});

function efbSendPasswordCode(WP_REST_Request $request){

    // Recaptcha
    $recaptchaResponse = wp_slash($request->get_param('g-recaptcha-response') ?? '');
    $email = sanitize_email($request->get_param('email'));

    $auth = new EmuAuthenticate;

    if(!$auth->recaptchaVerify($recaptchaResponse)){
        return new WP_REST_Response(['errors' => 'Recaptcha inválido.'], 200);
    };

    return $auth->sendResetKey($email) 
    ? new WP_REST_Response(['ok' => 1], 200) 
    : new WP_REST_Response(['errors' => 'E-mail não encontrado'], 200);

}