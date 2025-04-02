<?php

// acho que não precisa verificar por que é um endpoint de rest api do wp, mas deixa assim
if ( ! defined('ABSPATH')) return;

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

function efbResetPassword(WP_REST_Request $request) {

    $auth = new EmuAuthenticate;

    $reset_key = wp_slash($request->get_param('resetKey'));
    $password = wp_slash($request->get_param('password'));

    $userByKey = $auth->userByResetKey($reset_key);

    if( ! $userByKey) {
        return new WP_REST_Response(['errors' => 'Código inválido'], 200);
    }
    
    if ($password){
        
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=!])[A-Za-z\d@#$%^&+=!]{8,16}$/', $password)) {
            return new WP_REST_Response(['errors' => 'Senha inválida. A senha deve ter entre 8 e 16 caracteres, incluindo pelo menos uma letra, um número e um símbolo (@#$%^&+=!).'], 400);
        }

        // está tudo certo, definindo a nova senha!
        wp_set_password($password, $userByKey->ID);

        // excluindo o user meta de reset
        delete_user_meta($userByKey->ID, 'reset_password_key');
        delete_user_meta($userByKey->ID, 'reset_password_expiration');
        return new WP_REST_Response(['ok' => 1], 200);
    }

    return new WP_REST_Response(['errors' => 'Insira uma senha'], 200);

}
