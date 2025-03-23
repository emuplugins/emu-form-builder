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

    // caso seja enviado o e-mail, mas não seja válido
    if($request->get_param('email') && $email == false) return new WP_REST_Response(['errors' => 'E-mail inválido'], 200);

    $user_data = get_user_by('email', $email);

    if (!$user_data) {
        return new WP_REST_Response(['errors' => 'E-mail não encontrado'], 200);
    }

    $auth->sendResetKey($user_data->user_email, $user_data->ID);

}
