// capturando os formulários
const efbLoginForm = document.getElementById('efb-login-form');
const efbRegisterForm = document.getElementById('efb-register-form');
const efbSendPasswordEmailForm = document.getElementById('efb-send-password-email-form');
const efbResetPasswordForm = document.getElementById('efb-reset-password-form');
const efbConfirmCodeForm = document.getElementById('efb-confirm-code');

// capturando alguns inputs
const efbConfirmCodeInput = document.getElementById('efb-confirm-code-input');
const efbResetKeyInput = document.getElementById('efb-reset-key-input');

var loginRecaptchaWidget = efbLoginForm.querySelector('.g-recaptcha-element');
var RegisterRecaptchaWidget = efbRegisterForm.querySelector('.g-recaptcha-element');
var SendPasswordRecaptchaWidget = efbSendPasswordEmailForm.querySelector('.g-recaptcha-element');

// definindo os recaptcha Widgets
grecaptcha.ready(function() {

    loginRecaptchaWidget = grecaptcha.render(loginRecaptchaWidget,{
        'sitekey' : '6LfdJP0qAAAAAKkEyLb0goEc3cjmLWw10OF5_Qu7'
    });
    RegisterRecaptchaWidget = grecaptcha.render(RegisterRecaptchaWidget,{
        'sitekey' : '6LfdJP0qAAAAAKkEyLb0goEc3cjmLWw10OF5_Qu7'
    });
    SendPasswordRecaptchaWidget = grecaptcha.render(SendPasswordRecaptchaWidget,{
        'sitekey' : '6LfdJP0qAAAAAKkEyLb0goEc3cjmLWw10OF5_Qu7'
    });

});

// FUNCTIONS

// mostra as mensagens
function efbReturnResponse(message = null, cssClass = null, clearNotices = true) {
    const noticesElement = document.querySelector('#efb-notices');

    if(clearNotices){
        document.querySelector('#efb-notices').innerHTML = '';
    }
    
    // Criar uma nova div para a mensagem de erro
    const noticeDiv = document.createElement('div');
    
    // Adicionar a classe 'emu-notice' e a classe do tipo (por exemplo, 'emu-notices-danger')
    noticeDiv.classList.add('emu-notices', cssClass);
    
    // Definir o conteúdo da mensagem
    noticeDiv.textContent = message;
    
    // Adicionar a nova div dentro de #efb-notices
    noticesElement.appendChild(noticeDiv);
    
    // Verificar se o container de erros está oculto e alternar a exibição
    if (noticesElement.style.display === 'none') {
        noticesElement.style.display = 'block';
    }
}
// verifica se o recaptcha está vazio, a validação do código é feita no backend pela classe auth no php
function recaptchaVerify(widgetId) {

    // Verifica a resposta do reCAPTCHA
    if (grecaptcha.getResponse(widgetId) === "") {
        // Se a resposta for vazia, exibe a mensagem de erro
        efbReturnResponse('Confirme que você não é um robô.', 'emu-notices-danger');
        return false;
    }

    // Se tudo estiver correto, retorna true
    return true;
}
// verifica a força do password com base nos valores, caso esteja vazio retorna também
function verifyPassword(password, confirm) {
    // Verifica se os campos de senha e confirmação não estão vazios
    if (password === '' || confirm === '') {
        efbReturnResponse('Campos de senha vazios', 'emu-notices-danger');
        return false;
    }

    // Verifica se a senha tem pelo menos 8 caracteres, incluindo letras e números
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

    if (!passwordRegex.test(password)) {
        efbReturnResponse('A senha deve ter pelo menos 8 caracteres, incluindo letras e números.', 'emu-notices-danger');
        return false;
    }

    if (password !== confirm) {
        efbReturnResponse('As senhas não são iguais.', 'emu-notices-danger');
        return false;
    }

    return true;
}

// FETCHS

// verifica se o código é válido
function verifyResetCode(formValues){
    fetch(apiData.url + 'confirm-code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': apiData.nonce,
        },
        body: JSON.stringify({
            resetKey: formValues,
        })
    })    
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            efbReturnResponse('Código de confirmação confirmado.', 'emu-notices')
            efbConfirmCodeForm.style.display = efbConfirmCodeForm.style.display === 'none' ? 'flex' : 'none'; 
            efbResetPasswordForm.style.display  = efbResetPasswordForm.style.display  === 'none' ? 'flex' : 'none'; 
            console.log(data)
        }
        if (data.errors) {
            console.log(data)
            efbReturnResponse(data.errors, 'emu-notices-danger')
        }
    })
    .catch(error => {
        efbReturnResponse(`erro 4${error}`, 'emu-notices-danger');
    });
}
// tenta fazer o login
function efbTryLogin(formValues){

    fetch(apiData.url + 'login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': apiData.nonce,
        },
        body: JSON.stringify(formValues)
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            efbReturnResponse('Login bem sucedido. Bem-vindo de volta, ' + data.name + ' !', 'emu-notices-success', true)
            setTimeout(() => {
                location.reload();
        }, 3000);
        }
        if (data.error) {
            grecaptcha.reset(loginRecaptchaWidget)
            efbReturnResponse(data.error, 'emu-notices-danger')
        }
    })
    .catch(error => {
        efbReturnResponse(`Algo deu errado, entre em contato com nosso suporte! Detalhes do erro: ${error}`, 'emu-notices-danger');
    });

}
// tenta criar conta
function efbTryRegister(formValues){

    fetch(apiData.url + 'register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': apiData.nonce,
        },
        body: JSON.stringify(formValues)
    })
    .then(response => response.json())
    .then(data => {

        console.log(data)

        if (data.ok) {
            efbReturnResponse('Conta criada!. Redirecionando...', 'emu-notices-success', true)
            setTimeout(() => {
                location.reload();
        }, 3000);
        }
        if (data.errors) {

            document.querySelector('#efb-notices').innerHTML = '';
            grecaptcha.reset(RegisterRecaptchaWidget)
            // se existirem vários erros
            if (typeof data.errors === 'object' && !Array.isArray(data.errors)) {
                Object.keys(data.errors).forEach(key => {
                    const errorMessage = `${key}: ${data.errors[key]}`;
                    efbReturnResponse(errorMessage, 'emu-notices-danger', false);
                });
            }else if (typeof data.errors === 'string') {
                efbReturnResponse(data.errors, 'emu-notices-danger', false);
            } 
        }
        
    })
    .catch(error => {
        efbReturnResponse(`erro${error}`, 'emu-notices-danger');
    });

}
// tenta enviar o email de redefinição de senha
function efbSendPasswordEmail(formValues){

    fetch(apiData.url + 'send-password-code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': apiData.nonce,
        },
        body: JSON.stringify(formValues)
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            efbReturnResponse('E-mail de verificação enviado.', 'emu-notices')
            efbConfirmCodeForm.style.display = efbConfirmCodeForm.style.display === 'none' ? 'flex' : 'none'; 
            efbSendPasswordEmailForm.style.display  = efbSendPasswordEmailForm.style.display  === 'none' ? 'flex' : 'none';
        }
        if (data.errors) {
            grecaptcha.reset(SendPasswordRecaptchaWidget)
            efbReturnResponse(data.errors, 'emu-notices-danger')
        }
    })
}
// tenta mudar a senha
function efbTryChangePassword(formValues){

    fetch(apiData.url + 'reset-password', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': apiData.nonce,
        },
        body: JSON.stringify(formValues)
    })
    .then(response => response.json())
    .then(data => {
        console.log(data)
        if (data.ok) {
            efbReturnResponse('Senha alterada! Redirecionando...', 'emu-notices-success')
            setTimeout(() => {
                location.reload();
        }, 3000);
        }
        if (data.errors) {
            efbReturnResponse(data.errors, 'emu-notices-danger')
        }
    })
    .catch(error => {
        efbReturnResponse(`erro3 ${error}`, 'emu-notices-danger');
    });

}

// ACTIONS

// Adiciona event listeners nos formularios, enviando os campos para o backend
if (efbLoginForm){
efbLoginForm.addEventListener('submit', (e) =>{

    e.preventDefault()
    
    if ( ! recaptchaVerify(loginRecaptchaWidget) ) return false;
    const formData = new FormData(efbLoginForm);

    const formValues = Object.fromEntries(formData.entries());

    efbTryLogin(formValues)

})
}
if (efbRegisterForm){

efbRegisterForm.addEventListener('submit', (e) =>{

    e.preventDefault()

    registerPasswordInput = efbRegisterForm.querySelector('#register-password');
    registerPasswordConfirmInput = efbRegisterForm.querySelector('#register-password-confirm');

    if ( ! verifyPassword(registerPasswordInput.value, registerPasswordConfirmInput.value)) return false;

    if ( ! recaptchaVerify(RegisterRecaptchaWidget) ) return false;

    const formData = new FormData(efbRegisterForm);
    
    const formValues = Object.fromEntries(formData.entries());

    efbTryRegister(formValues)

})

}
if (efbSendPasswordEmailForm){
    
efbSendPasswordEmailForm.addEventListener('submit', (e) =>{

    e.preventDefault()
    if ( ! recaptchaVerify(SendPasswordRecaptchaWidget) ) return false;

    const formData = new FormData(efbSendPasswordEmailForm);
    const formValues = Object.fromEntries(formData.entries());

    efbSendPasswordEmail(formValues)

})

}
if (efbResetPasswordForm){

efbResetPasswordForm.addEventListener('submit', (e) =>{

    e.preventDefault()

    newPasswordInput = efbResetPasswordForm.querySelector('#new-password');
    newPasswordConfirmInput = efbResetPasswordForm.querySelector('#confirm-new-password');

    if ( ! verifyPassword(newPasswordInput.value, newPasswordConfirmInput.value)) return false;

    const formData = new FormData(efbResetPasswordForm);

    const formValues = Object.fromEntries(formData.entries());

    efbTryChangePassword(formValues)

})
}
if (efbConfirmCodeInput){

efbConfirmCodeInput.addEventListener('input', (e)=>{
    
    if (e.target && e.target.value && e.target.value.length >= 5) {
        e.target.value = e.target.value.substring(0,5);

        e.target.setAttribute('readonly', true);

        verifyResetCode(e.target.value.substring(0, 5));

        setTimeout(()=>{
            e.target.removeAttribute('readonly');
        }, 500)
    }
    efbResetKeyInput.value = e.target.value;
})

}