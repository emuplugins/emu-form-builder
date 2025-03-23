<?php

// acho que não precisa verificar por que é um endpoint de rest api do wp, mas deixa assim
if ( ! defined('ABSPATH')) return;

//  =================================
//  ENDPOINT PARA CONFIRMAR CÓDIGO DE REDEFINIÇÃO DE SENHA
// ==================================

add_action('rest_api_init', function() {

    register_rest_route('emu_plugins/v1', 'confirm-code/', [
        'methods' => 'POST', 
        'callback' => 'efbConfirmCode',
        'permission_callback' => '__return_true', 
    ]);

});

function efbConfirmCode(WP_REST_Request $request) {

    $auth = new EmuAuthenticate;
    
    $reset_key = wp_slash($request->get_param('resetKey'));

    $userByKey= $auth->userByResetKey($reset_key);

    // se retornar o usuário, ok
    if($userByKey){

        return new WP_REST_Response(['ok' => 1], 200);
        
    }

    return new WP_REST_Response(['errors' => 'Código expirado, ou inválido'], 200);
}