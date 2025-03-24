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

    inputs.forEach((input, index) => {
        // Ignora os inputs hidden e checkbox
        if (input.type !== 'hidden' && input.type !== 'checkbox') {
            input.name = 'building';
            input.type = 'text';
            input.removeAttribute('autocomplete');
            
            // Adiciona o valor "Texto exemplo" ao primeiro campo
            if (index === 0) {
                input.value = 'Texto exemplo';
            } else {
                input.value = ''; // Limpa os outros campos
            }
        }
    });
}

window.addEventListener('emu_form_ready', () => {

    const stepsButtons = document.querySelectorAll('.efb-steps li');
    const stepSections = document.querySelectorAll('.efb-multistep .step');

    stepsButtons.forEach((s) => {
        s.addEventListener('click', (e) => {

             // Se não estiver ativo, oculta o notices
             if (!s.classList.contains('active')) {
                document.querySelector('#efb-notices').style.display = 'none';
            }

            const stepClicked = e.target.getAttribute('step');

            // Remover a classe 'active' de todos os botões antes de adicionar ao selecionado
            stepsButtons.forEach(btn => btn.classList.remove('active'));

            // Mostrar ou esconder as seções
            stepSections.forEach(sc => {
                const stepNumber = sc.getAttribute('step');

                if (stepNumber === stepClicked) {
                    sc.style.display = 'block';  // Exibe a seção
                    e.target.classList.add('active');  // Adiciona a classe 'active' ao botão clicado
                } else {
                    sc.style.display = 'none';  // Esconde as outras seções
                }
            });
        });
    });
});