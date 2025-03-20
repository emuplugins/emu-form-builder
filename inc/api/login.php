<?php

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
    
    $username = sanitize_text_field($request->get_param('username'));
    $password = wp_slash($request->get_param('password'));

    $user = wp_authenticate($username, $password);	

    if (is_wp_error($user)) {
        return new WP_REST_Response(['error' => 'login incorrect'], 200);
    }
	// Após todas as verificações, já que o login deu certo definimos o cookie.
	wp_set_auth_cookie($user->ID, true);

	$user_info = get_userdata($user->id);

	return new WP_REST_Response(['ok' => 1, 'name' => $user_info->display_name], 200);
}