(function () {
    'use strict';

    var container = document.getElementById('containerItens');
    var botaoAdicionar = document.getElementById('btnAdicionarItem');

    if (!container || !botaoAdicionar) {
        return;
    }

    function criarLinha() {
        var linha = document.createElement('div');
        linha.className = 'input-group mb-2';

        var input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control';
        input.name = 'itens[]';
        input.maxLength = 150;
        input.placeholder = 'Ex: Decoração temática';

        var botao = document.createElement('button');
        botao.type = 'button';
        botao.className = 'btn btn-outline-danger btn-remover-item';
        botao.textContent = 'Remover';

        linha.appendChild(input);
        linha.appendChild(botao);
        return linha;
    }

    botaoAdicionar.addEventListener('click', function () {
        container.appendChild(criarLinha());
    });

    container.addEventListener('click', function (event) {
        if (!event.target.classList.contains('btn-remover-item')) {
            return;
        }
        var linhas = container.querySelectorAll('.input-group');
        if (linhas.length > 1) {
            event.target.closest('.input-group').remove();
        } else {
            event.target.closest('.input-group').querySelector('input').value = '';
        }
    });
})();
