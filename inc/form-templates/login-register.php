<?php

if( ! defined('ABSPATH') ) exit;

// Retorna o formulário com os estilos e scripts incorporados
function efb_login_register(){

    if ( isset( $_GET['efb'] ) && $_GET['efb'] === 'rp' ) {

        $user_id = $_GET['id'];

        $user = $user_id;

        $reset_key = $_GET['key'] ?? null;

        if ( $user ) {
            
            $stored_key = get_user_meta( $user_id, 'reset_password_key', true );
            $expiration = get_user_meta( $user_id, 'reset_password_expiration', true );

            if ( $stored_key === $reset_key && time() < $expiration ) {
               ?>

               Link válido!
               
               <div class="efb-multistep">

               <div style="display:none;" id="efb-notices"></div>

                    <div class="step" step="1">

                        <form class="efb-form" method="POST" action="" id="efb-change-password-form">

                            <input type="hidden" name="userId" value="<?php echo $_GET['id'] ?? '' ?>">
                            <input type="hidden" name="resetKey" value="<?php echo $_GET['key'] ?? '' ?>">

                            <div class="efb-form-group">    
                                <label for="">Senha</label>
                                <input type="text" name="password">
                            </div>

                            <div class="efb-form-group">    
                                <label for="">Repita a senha</label>
                                <input type="text" name="passwordConfirm">
                            </div>

                            <div class="efb-form-group">
                                <button class="emu-btn emu-btn-primary">Alterar senha</button>
                            </div>
                        </form>

                    </div>

               </div>
               
               <?php
            } else {
                ?>
               
               <div class="efb-multistep">

                <div id="efb-notices">Link de redefinição expirado ou inválido.</div>
               
               </div>
               <?php
            }
        }else{
            ?>
               
               <div class="efb-multistep">

                <div id="efb-notices">não foi possivel recuperar os dados do usuário.</div>
               
               </div>
               <?php
        }
    } else {

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
                            <label for="">Nome de usuário</label>
                            <input type="text" name="username" required> 
                        </div>

                        <div class="efb-form-group">    
                            <label for="">Senha</label>
                            <input type="text" name="password" required>
                        </div>

                        <div class="efb-form-group">    
                            <label for="remember">
                            <input type="checkbox" name="remember">
                            Lembrar senha?</label>
                        </div>

                        <div class="efb-form-group">
                            <button class="emu-btn emu-btn-primary">Entrar</button>
                        </div>
                    </form>

                </div>

                <div class="step" style="display: none" step="2">
                    <form class="efb-form" id="efb-register-form">

                        <div class="efb-form-group">    
                            <label for="">Nome de usuário</label>
                            <input type="text" name="username">
                        </div>
                        
                        <div class="efb-form-group">    
                            <label for="">E-mail</label>
                            <input type="text" name="email">
                        </div>

                        <div class="efb-form-group">    
                            <label for="">Senha</label>
                            <input type="text" name="password">
                        </div>

                        <div class="efb-form-group">    
                            <label for="">Repita a senha</label>
                            <input type="text" name="passwordConfirm">
                        </div>
                        
                        <div class="efb-form-group">
                            <button class="emu-btn emu-btn-primary">Registrar</button>
                        </div>

                    </form>
                </div>

                <div class="step" style="display: none" step="3">
                    <form class="efb-form" id="efb-reset-password-form">

                        <div class="efb-form-group">    
                            <label for="">E-mail</label>
                            <input type="text" name="email">
                        </div>
                       
                        <input type="hidden" value="<?php echo esc_url(home_url( parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) )); ?>" name="formUrl">

                        <div class="efb-form-group">
                            <button class="emu-btn emu-btn-primary">Enviar e-mail</button>
                        </div>

                    </form>
                </div>
            </div>

        <?php
    
}
function efbLoginScript() {

    wp_enqueue_style(
        'emu-login-handler',
        PLUGIN_URL . 'assets/css/form.css'
    );
    wp_enqueue_script(
        'emu-form-js',
        PLUGIN_URL . 'assets/js/form.js',
        array(),
        null,
        true 
    );
    wp_enqueue_script(
        'emu-login-handler',
        PLUGIN_URL . 'assets/js/login.js',
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

efbLoginScript();


}