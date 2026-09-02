(function () {
    'use strict';

    function aplicarMascaraCpf(campo) {
        campo.addEventListener('input', function () {
            var v = campo.value.replace(/\D/g, '').slice(0, 11);
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            campo.value = v;
        });
    }

    function aplicarMascaraTelefone(campo) {
        campo.addEventListener('input', function () {
            var v = campo.value.replace(/\D/g, '').slice(0, 11);
            if (v.length > 10) {
                v = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (v.length > 5) {
                v = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else if (v.length > 2) {
                v = v.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            }
            campo.value = v;
        });
    }

    document.querySelectorAll('[data-mascara="cpf"]').forEach(aplicarMascaraCpf);
    document.querySelectorAll('[data-mascara="telefone"]').forEach(aplicarMascaraTelefone);
})();
