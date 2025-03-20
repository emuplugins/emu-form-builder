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

function efbResetPassword(WP_REST_Request $request) {

    // se não estiver, vamos usar só o e-mail
    $email = sanitize_email($request->get_param('email')) ?? '';
    $formUrl = esc_url_raw($request->get_param('formUrl')) ?? '';
    $reset_key = wp_slash($request->get_param('resetKey')) ?? '';
    $user_id = sanitize_text_field($request->get_param('userId')) ?? '';
    $password = wp_slash($request->get_param('password')) ?? '';

    if(!$email && !$reset_key){
        return new WP_REST_Response(['errors' => 'invalid email'], 200);
    }


    if($email){

        $user_data = get_user_by( 'email', $email );
        $reset_key = wp_generate_password( 20, false );

        if (!$user_data) {
            return new WP_REST_Response(['errors' => 'email not found'], 200);
        }

        update_user_meta( $user_data->ID, 'reset_password_key', $reset_key );
        update_user_meta( $user_data->ID, 'reset_password_expiration', time() + 450 ); // 7.30 minutos

        $reset_link = ($formUrl . "?efb=rp&key=$reset_key&id=" . $user_data->ID );
        
        wp_mail( $user_data->user_email, 'Redefinição de senha', 'Clique no link para redefinir sua senha: ' . $reset_link );

        return new WP_REST_Response(['ok' => 1], 200);
    }

    // já que não foi enviado e-mail, vamos tentar mudar a senha, caso o hash esteja correto.

    $stored_key = get_user_meta( $user_id, 'reset_password_key', true );
    $expiration = get_user_meta( $user_id, 'reset_password_expiration', true );

    if ( $stored_key === $reset_key && time() < $expiration ) {

        wp_set_password( $password, $user_id ); // Altera a senha do usuário com ID 5

        delete_user_meta( $user_id, 'reset_password_key' );
        delete_user_meta( $user_id, 'reset_password_expiration' );
        return new WP_REST_Response(['ok' => 1], 200);

    }else {

        delete_user_meta( $user_id, 'reset_password_key' );
        delete_user_meta( $user_id, 'reset_password_expiration' );
        return new WP_REST_Response(['errors' => 'reset_key invalid'], 200);
    }

}