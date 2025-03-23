<?php

if( ! defined('ABSPATH') ) exit;

function efbLoginScript() {

    wp_enqueue_script(
        'google-recaptcha',
        'https://www.google.com/recaptcha/api.js'
    );

    wp_enqueue_style(
        'emu-login-handler',
        EFB_PLUGIN_URL . 'assets/css/form.css'
    );

    wp_enqueue_script(
        'emu-form-js',
        EFB_PLUGIN_URL . 'assets/js/form.js',
        array(),
        null,
        true 
    );

    wp_enqueue_script(
        'emu-login-handler',
        EFB_PLUGIN_URL . 'assets/js/login.js',
        array(),
        null,
        true 
    );

    $nonce = wp_create_nonce( 'wp_rest' );
    wp_localize_script( 'emu-login-handler', 'apiData', [
        'nonce' => $nonce,
        'url'   => rest_url( 'emu_plugins/v1/' ),
    ]); 
}

// Retorna o formulário com os estilos e scripts incorporados
function efb_login_register(){

    $gSiteKey = '6LfdJP0qAAAAAKkEyLb0goEc3cjmLWw10OF5_Qu7';

    ?>
<div class="efb-multistep">
    
    <div class="efb-steps">
        <ul>
            <li step="1" class="active">Login</li>
            <li step="2">Registrar-se</li>
            <li step="3">Redefinir Senha</li>
        </ul>
    </div>
    
    <div style="display:none;" id="efb-notices"></div>
    
    <div class="step" step="1">
        <form class="efb-form" method="POST" action="" id="efb-login-form">
            <div class="efb-form-group">    
                <label for="login-username">Nome de usuário</label>
                <input type="text" name="username" id="login-username" required autocomplete="username"> 
            </div>

            <div class="efb-form-group">    
                <label for="login-password">Senha</label>
                <input type="password" name="password" id="login-password" required autocomplete="current-password">
            </div>

            <div class="efb-form-group">    
                <label for="remember">
                    <input type="checkbox" name="remember" id="remember">
                    Lembrar senha?
                </label>
            </div>

            <div class="efb-form-group">
                <div class="g-recaptcha-element" data-sitekey="<?php echo $gSiteKey ?>"></div>
            </div>

            <div class="efb-form-group">
                <button class="emu-btn emu-btn-primary">Entrar</button>
            </div>
        </form>
    </div>

    <div class="step" style="display: none" step="2">
        <form class="efb-form" id="efb-register-form">
            <div class="efb-form-group">    
                <label for="register-username">Nome de usuário</label>
                <input type="text" name="username" id="register-username" autocomplete="username">
            </div>
            
            <div class="efb-form-group">    
                <label for="register-email">E-mail</label>
                <input type="text" name="email" id="register-email" autocomplete="email">
            </div>

            <div class="efb-form-group">    
                <label for="register-password">Senha</label>
                <input type="password" name="password" id="register-password" autocomplete="new-password">
            </div>

            <div class="efb-form-group">    
                <label for="register-password-confirm">Repita a senha</label>
                <input type="password" name="passwordConfirm" id="register-password-confirm" autocomplete="new-password">
            </div>

            <div class="efb-form-group">
                <div class="g-recaptcha-element" data-sitekey="<?php echo $gSiteKey ?>"></div>
            </div>
            
            <div class="efb-form-group">
                <button class="emu-btn emu-btn-primary">Registrar</button>
            </div>
        </form>
    </div>

    <div class="step" style="display: none" step="3">
        <form class="efb-form" id="efb-send-password-email-form">
            <h2>Insira seu e-mail</h2>

            <div class="efb-form-group">    
                <label for="reset-email">E-mail</label>
                <input type="text" name="email" id="reset-email">
            </div>

            <div class="g-recaptcha-element" data-sitekey="<?php echo $gSiteKey ?>"></div>

            <div class="efb-form-group">
                <button class="emu-btn emu-btn-primary">Enviar e-mail</button>
            </div>
        </form>

        <form class="efb-form" id="efb-confirm-code" style="display:none">
            <h2>Confirme seu código</h2>

            <div class="efb-form-group">    
                <label for="efb-confirm-code-input">Insira o código aqui</label>
                <input type="number" name="resetKey" id="efb-confirm-code-input">
            </div>
        </form>
        
        <form class="efb-form" id="efb-reset-password-form" style="display:none">
            <h2>Redefinir senha</h2>

            <input type="hidden" id="response-hash">
            <input type="hidden" name="resetKey" value="" id="efb-reset-key-input">
            <input type="email" name="username" autocomplete="username email" style="display:none">

            <div class="efb-form-group">    
                <label for="new-password">Senha</label>
                <input type="password" name="password" id="new-password" autocomplete="new-password">
            </div>
            <div class="efb-form-group">    
                <label for="confirm-new-password">Confirme a senha</label>
                <input type="password" name="confirmpassword" id="confirm-new-password" autocomplete="new-password">
            </div>

            <div class="efb-form-group">
                <button class="emu-btn emu-btn-primary">Redefinir senha</button>
            </div>
        </form>
    </div>
</div>


    <?php

    
    efbLoginScript();

    return;
}