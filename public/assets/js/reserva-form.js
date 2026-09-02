(function () {
    'use strict';

    var selectSalao = document.getElementById('salao_id');
    var inputConvidados = document.getElementById('numero_convidados');
    var avisoCapacidade = document.getElementById('avisoCapacidade');

    if (!selectSalao || !inputConvidados) {
        return;
    }

    function atualizarCapacidade() {
        var opcao = selectSalao.options[selectSalao.selectedIndex];
        var capacidade = opcao ? opcao.getAttribute('data-capacidade') : null;

        if (!capacidade) {
            return;
        }

        inputConvidados.max = capacidade;
        if (avisoCapacidade) {
            avisoCapacidade.textContent = 'Capacidade máxima deste salão: ' + capacidade + ' convidados.';
        }

        if (inputConvidados.value && parseInt(inputConvidados.value, 10) > parseInt(capacidade, 10)) {
            inputConvidados.setCustomValidity('O número de convidados excede a capacidade do salão selecionado.');
        } else {
            inputConvidados.setCustomValidity('');
        }
    }

    selectSalao.addEventListener('change', atualizarCapacidade);
    inputConvidados.addEventListener('input', atualizarCapacidade);
    atualizarCapacidade();
})();
