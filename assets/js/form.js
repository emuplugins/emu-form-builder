document.addEventListener('DOMContentLoaded', () => {
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
