<?php

if( ! defined('ABSPATH') ) exit;

// Retorna o formulário com os estilos e scripts incorporados
function efb_login_register($gSiteKey = false){
    
    ?>
    
    <div class="efb-steps">
        <ul>
            <li step="1" class="active">Login</li>
            <li step="2">Registrar-se</li>
            <li step="3">Redefinir Senha</li>
        </ul>
    </div>
    
    <div style="display:none;" id="efb-notices"></div>
    <input type="hidden" id="efb-recaptcha-site-key" value="<?php echo $gSiteKey ?>">
    
    <div class="step" step="1">
        <form class="efb-form" method="POST" action="" id="efb-login-form">
            <h2 class="efb-step-title">Faça seu login</h2>
            <div class="efb-form-group">    
                <label for="login-username">Nome de usuário</label>
                <input type="text" name="username" id="login-username" required autocomplete="username"> 
            </div>

            <div class="efb-form-group">    
                <label for="login-password">Senha</label>
                <input type="password" name="password" id="login-password" required autocomplete="current-password">
            </div>

            <div class="efb-form-group">    
                <label for="remember" style="display: flex;gap: 10px;flex-direction: row;align-items: center;">
                    <input type="checkbox" name="remember" id="remember">
                    Lembrar senha?
                </label>
            </div>
            <?php if($gSiteKey) : ?>
                <div class="efb-form-group">
                    <div class="g-recaptcha-element" data-sitekey="<?php echo $gSiteKey ?>"></div>
                </div>
            <?php endif; ?>

            <div class="efb-form-group">
                <button class="emu-btn emu-btn-primary">Entrar</button>
            </div>
        </form>
    </div>

    <div class="step" style="display: none" step="2">
        <form class="efb-form" id="efb-register-form">
            <h2 class="efb-step-title">Crie sua conta</h2>

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
            <?php if($gSiteKey) : ?>
                <div class="efb-form-group">
                    <div class="g-recaptcha-element" data-sitekey="<?php echo $gSiteKey ?>"></div>
                </div>
            <?php endif; ?>
            <div class="efb-form-group">
                <button class="emu-btn emu-btn-primary">Registrar</button>
            </div>
        </form>
    </div>

    <div class="step" style="display: none" step="3">
        <form class="efb-form" id="efb-send-password-email-form">
            <h2 class="efb-step-title">Recupere sua senha</h2>

            <div class="efb-form-group">    
                <label for="reset-email">E-mail</label>
                <input type="text" name="email" id="reset-email">
            </div>
            <?php if($gSiteKey) : ?>
                <div class="g-recaptcha-element" data-sitekey="<?php echo $gSiteKey ?>"></div>
            <?php endif; ?>
            <div class="efb-form-group">
                <button class="emu-btn emu-btn-primary">Enviar e-mail</button>
            </div>
        </form>

        <form class="efb-form" id="efb-confirm-code" style="display:none">
            <h2 class="efb-step-title">Confirme o código</h2>

            <div class="efb-form-group">    
                <label for="efb-confirm-code-input">Insira o código aqui</label>
                <input type="number" name="resetKey" id="efb-confirm-code-input">
            </div>
        </form>
        
        <form class="efb-form" id="efb-reset-password-form" style="display:none">
            <h2 class="efb-step-title">Altere sua senha</h2>

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
<script>
	
document.addEventListener('DOMContentLoaded', ()=>{
	EmuFormReady()

})
</script>
    <?php

    
    // efbLoginScript();

}

add_shortcode('emu_form_builder', 'efb_login_register');
