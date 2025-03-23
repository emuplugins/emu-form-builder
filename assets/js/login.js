// const siteKey = '6LdvVfoqAAAAAIUxyeWpcnjDAlQbV7Lcu6rH4AQt';

const efbLoginForm = document.getElementById('efb-login-form')
const efbRegisterForm = document.getElementById('efb-register-form')
const efbResetPasswordForm = document.getElementById('efb-reset-password-form')
const efbChangePasswordForm = document.getElementById('efb-change-password-form')

// FUNCTIONS

function validarRecaptcha(){
    return false;
}

// NOTICES
function efbReturnResponse(message = null, type = null, clearNotices = false) {
    const noticesElement = document.querySelector('#efb-notices');

    if(clearNotices){
        document.querySelector('#efb-notices').innerHTML = '';
    }
    
    // Criar uma nova div para a mensagem de erro
    const noticeDiv = document.createElement('div');
    
    // Adicionar a classe 'emu-notice' e a classe do tipo (por exemplo, 'emu-notices-danger')
    noticeDiv.classList.add('emu-notices', type);
    
    // Definir o conteúdo da mensagem
    noticeDiv.textContent = message;
    
    // Adicionar a nova div dentro de #efb-notices
    noticesElement.appendChild(noticeDiv);
    
    // Verificar se o container de erros está oculto e alternar a exibição
    if (noticesElement.style.display === 'none') {
        noticesElement.style.display = 'block';
    }
}

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
        //     setTimeout(() => {
        //         location.reload();
        // }, 3000);
        }
        if (data.error) {
            efbReturnResponse(data.error, 'emu-notices-danger', true)
        }
    })
    .catch(error => {
        efbReturnResponse(`erro ${error}`, 'danger', true);
    });

}

// REGISTER
function efbTryRegister(formValues){

    console.log(formValues)

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

            // Se data.errors for um objeto com múltiplos erros (com chaves)
            if (typeof data.errors === 'object' && !Array.isArray(data.errors)) {
                Object.keys(data.errors).forEach(key => {
                    const errorMessage = `${key}: ${data.errors[key]}`;
                    efbReturnResponse(errorMessage, 'emu-notices-danger');
                });
            } 

            // Se data.errors for um erro único (string)
            else if (typeof data.errors === 'string') {
                efbReturnResponse(data.errors, 'emu-notices-danger');
            } 
        }
        
    })
    .catch(error => {
        efbReturnResponse(`erro ${error}`, 'danger', true);
    });

}

// Reset Password
function efbTryResetPassword(formValues){

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
            efbReturnResponse('E-mail de verificação enviado.', 'emu-notices', true)
        }
        if (data.errors) {
            efbReturnResponse(data.errors, 'emu-notices-danger', true)
        }
    })
    .catch(error => {
        efbReturnResponse(`erro ${error}`, 'danger', true);
    });

}

// Change Password
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
            efbReturnResponse('Senha alterada! Redirecionando...', 'emu-notices-success', true)
        }
        if (data.errors) {
            efbReturnResponse(data.errors, 'emu-notices-danger', true)
        }
    })
    .catch(error => {
        efbReturnResponse(`erro ${error}`, 'danger', true);
    });

}

// ACTIONS
// Adiciona event listeners nos formularios, enviando os campos para o backend
if (efbLoginForm){

efbLoginForm.addEventListener('submit', (e) =>{

    e.preventDefault()

    const formData = new FormData(efbLoginForm);

    const formValues = Object.fromEntries(formData.entries());

    efbTryLogin(formValues)

})

}

if (efbRegisterForm){

efbRegisterForm.addEventListener('submit', (e) =>{

    e.preventDefault()

    const formData = new FormData(efbRegisterForm);

    const formValues = Object.fromEntries(formData.entries());

    efbTryRegister(formValues)

})

}

if (efbResetPasswordForm){
    
efbResetPasswordForm.addEventListener('submit', (e) =>{

    e.preventDefault()

    const formData = new FormData(efbResetPasswordForm);

    const formValues = Object.fromEntries(formData.entries());

    efbTryResetPassword(formValues)

})

}
if (efbChangePasswordForm){

efbChangePasswordForm.addEventListener('submit', (e) =>{

    e.preventDefault()

    const formData = new FormData(efbChangePasswordForm);

    const formValues = Object.fromEntries(formData.entries());

    efbTryChangePassword(formValues)

})
}