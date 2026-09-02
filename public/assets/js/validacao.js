(function () {
    'use strict';

    // Ativa o padrao de validacao visual do Bootstrap (classes is-invalid / invalid-feedback)
    // em todo formulario marcado com "needs-validation".
    document.querySelectorAll('form.needs-validation').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Confirmacao de senha, generico: funciona para qualquer par <id> / <id>_confirmacao
    // (cobre "senha"/"senha_confirmacao" no cadastro e "nova_senha"/"nova_senha_confirmacao" no perfil).
    document.querySelectorAll('input[id$="_confirmacao"]').forEach(function (campoConfirmacao) {
        var campoBase = document.getElementById(campoConfirmacao.id.replace(/_confirmacao$/, ''));
        if (!campoBase) {
            return;
        }
        var validarConfirmacao = function () {
            campoConfirmacao.setCustomValidity(campoConfirmacao.value !== campoBase.value ? 'Os valores não conferem.' : '');
        };
        campoBase.addEventListener('input', validarConfirmacao);
        campoConfirmacao.addEventListener('input', validarConfirmacao);
    });
})();
