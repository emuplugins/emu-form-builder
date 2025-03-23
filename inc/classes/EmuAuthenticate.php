<?php

if(!defined('ABSPATH'))exit;

class EmuAuthenticate {

    public $gSecretkey;

    public function __construct() {
        $this->gSecretkey = '6LfdJP0qAAAAALKnl2m_II3PahoyZw3Jq_rqstvl';
    }

    private function get_user_ip() {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP']; // Cloudflare
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP']; // Proxy transparente
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ip_list[0]); // Primeiro IP da lista
        } else {
            return $_SERVER['REMOTE_ADDR']; // IP direto do usuário
        }
    }

    public function userByResetKey($reset_key) {
        if (!$reset_key) {
            return false;
        }
    
        $users = get_users([
            'meta_key'   => 'reset_password_key',
            'meta_value' => $reset_key,
            'number'     => 1,
        ]);
    
        if (empty($users)) {
            return false;
        }
    
        $user = $users[0]; // Pegando o primeiro usuário encontrado
    
        // Obtém a chave e a expiração armazenadas
        $stored_key = get_user_meta($user->ID, 'reset_password_key', true);
        $expiration = get_user_meta($user->ID, 'reset_password_expiration', true);
    
        // Se a chave for válida e não estiver expirada, retorna o objeto usuário
        if ($stored_key === $reset_key && time() < (int) $expiration) {
            return $user; // Retorna o objeto WP_User diretamente
        }
    
        return false;
    }
    
    

    public function sendResetKey($email){
        
        $user = get_user_by('email', $email);
        $user_id = $user->ID;

        // envia a chave de verificação com base no e-mail do usuário

        $reset_key = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        update_user_meta($user_id, 'reset_password_key', $reset_key);
        update_user_meta($user_id, 'reset_password_expiration', time() + 1800);

        wp_mail($email, 'Redefinição de senha', "Código: $reset_key");

        return new WP_REST_Response(['ok' => 1], 200);
        // return true;

    }

    public function recaptchaVerify($recaptchaResponse){

        $recaptchaResponse = wp_slash($recaptchaResponse);
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'secret' => $this->gSecretkey,
                'response' => $recaptchaResponse,
            ]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response,true);
        
        if($response['success'] == false){
            return false;
        } 

        return true;
    }

}