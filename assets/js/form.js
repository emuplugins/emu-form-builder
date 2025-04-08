function EmuFormReady() {

    const eventoEmuForm = new CustomEvent('emu_form_ready', {
        bubbles: true,
        cancelable: true
    });

    dispatchEvent(eventoEmuForm);
}

function changeInputsByClass() {
    const element = document.querySelector('.efb-multistep');
    const inputs = element.querySelectorAll('input');

    setTimeout( ()=>{ 
        
            inputs.forEach((input, index) => {
            // console.log(input)
            // Ignora os inputs hidden e checkbox
            if (input.type !== 'hidden' && input.type !== 'checkbox') {
                input.name = 'building';
                input.type = 'text';
                input.value = ' ';
                input.removeAttribute('id');
                input.removeAttribute('autocomplete');
                
                // Adiciona o valor "Texto exemplo" ao primeiro campo
                setTimeout( ()=>{
                    if (index === 0) {
                        input.value = 'Texto exemplo';
                    } else {
                        input.value = ''; // Limpa os outros campos
                    }
                }, 400)
            }
        });
    }, 400)
}