<?php

if(!defined('ABSPATH'))exit;

class EmuAuthenticate {

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

        $user_data = get_user_by('email', $email);
        if ( ! $user_data) {
            return false;
        }

        // envia a chave de verificação com base no e-mail do usuário
        $user_id = $user_data->ID;
        $reset_key = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);

        // atualizando no banco de dados os dados de redefinição do usuario
        update_user_meta($user_id, 'reset_password_key', $reset_key);
        update_user_meta($user_id, 'reset_password_expiration', time() + 1800);

        wp_mail($email, 'Redefinição de senha', "Código: $reset_key");

        return true;

    }

    public function recaptchaVerify($recaptchaResponse){

        $gSiteKey = get_option('efb_grecaptcha_key');
        $gSecretKey = get_option('efb_grecaptcha_secret');

        if ( ! $gSiteKey ||  ! $gSecretKey){
            return true;
        }

        $recaptchaResponse = wp_slash($recaptchaResponse);
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'secret' => $gSecretKey,
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

    public function ValidateFields($data) {

        $errors = [];
    
        // Validação de nome de usuário
        if (empty($data['username'])) {
            $errors['username'] = 'O campo de nome de usuário está vazio';
        } elseif (strlen($data['username']) < 5) {
            $errors['username'] = 'O nome de usuário deve ter pelo menos 5 caracteres';
        } elseif (get_user_by('login', $data['username'])) {
            $errors['username'] = 'O nome de usuário já existe'; // O nome de usuário já existe
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $data['username'])) {
            $errors['username'] = 'Não é permitido símbolos no nome de usuário'; // Nome de usuário com caracteres inválidos
        }
    
        // Validação de e-mail
        if (empty($data['email'])) {
            $errors['email'] = 'O campo de e-mail está vazio'; // E-mail vazio
        } elseif (!sanitize_email($data['email'])) {
            $errors['email'] = 'E-mail inválido'; // E-mail inválido
        }
    
        // Validação de senha (mínimo de 8 caracteres, pelo menos uma letra, um número e um caractere especial)
        $passwordRegex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=!])[A-Za-z\d@#$%^&+=!]{8,16}$/';
        if (!preg_match($passwordRegex, $data['password'])) {
            $errors['password'] = 'Senha inválida'; // Senha inválida
        }
    
        return $errors; // Retorna o array de erros, caso existam
    }    


}
