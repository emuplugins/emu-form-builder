<?php

if ( ! defined('ABSPATH')) exit;

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

    // validando recaptcha
    $gSecretKey = '6LfdJP0qAAAAALKnl2m_II3PahoyZw3Jq_rqstvl';
    $gRecaptcha = wp_slash($request->get_param('g-recaptcha-response') ?? '');

    if ( ! $gRecaptcha) return new WP_REST_Response(['error' => 'recaptcha invalid'], 200);

    $curl = curl_init();

    // definindo curl

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => [
            'secret' => $gSecretKey,
            'response' => $gRecaptcha,
        ]
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($response,true);

    

    if($response['success'] == false) return new WP_REST_Response(['error' => 'recaptcha invalid'], 200);
    
    // limpando os dados...
    $username = sanitize_text_field($request->get_param('username'));
    $password = wp_slash($request->get_param('password'));

    // verificando se o login está correto

    $user = wp_authenticate($username, $password);	

    if (is_wp_error($user)) {
        return new WP_REST_Response(['error' => 'login incorrect'], 200);
    }

	// definindo o cookie que autentica o usuário
    // por incrivel que pareça, isso funciona com fetch! 😱

	wp_set_auth_cookie($user->ID, true);

	$user_info = get_userdata($user->id);

	return new WP_REST_Response(['ok' => 1, 'name' => $user_info->display_name], 200);
}