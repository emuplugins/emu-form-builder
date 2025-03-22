<?php

//  =================================
//  ENDPOINT PARA VERIFICAR O RECAPTCHA
// ==================================

add_action('rest_api_init', function() {
    register_rest_route('emu_plugins/v1', 'recaptcha-verify/', [
        'methods' => 'POST',
        'callback' => 'efbRecaptchaVerify',
        'permission_callback' => '__return_true', 
    ]);
});

function efbRecaptchaVerify(WP_REST_Request $request) {
    
    // Corrigir o nome do parâmetro
    $recaptchaToken = $request->get_param('recaptcha_token'); 

    // Sua chave secreta do reCAPTCHA
    $secretKey = '6LdvVfoqAAAAAIUxyeWpcnjDAlQbV7Lcu6rH4AQt';

    $remoteip = $_SERVER['REMOTE_ADDR'];

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaToken&remoteip=$remoteip");

    $responseKeys = json_decode($response, true);

    // Verifica se a resposta do reCAPTCHA é válida
    if(intval($responseKeys["success"]) !== 1) {

        return new WP_REST_Response([
            'ok' => 1, 
            'google_response' => $responseKeys // Retorna toda a resposta do Google
        ], 200);
        
    } else {
        return new WP_REST_Response([
            'errors' => 1, 
            'google_response' => $responseKeys// Retorna toda a resposta do Google
        ], 200);
    }
  
}
