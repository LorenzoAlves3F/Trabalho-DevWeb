<?php require ROOT_PATH . '/views/partials/header_cliente.php'; ?>
<div class="card card-carlores">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Data do pagamento</th><th>Salão</th><th>Data do evento</th><th>Forma</th><th>Tipo</th><th>Valor</th></tr></thead>
            <tbody>
            <?php foreach ($pagamentos as $p): ?>
                <tr>
                    <td><?= dateBr($p['data_pagamento']) ?></td>
                    <td><?= e($p['salao_nome']) ?></td>
                    <td><?= dateBr($p['reserva_data_evento']) ?></td>
                    <td><?= formaPagamentoLabel($p['forma_pagamento']) ?></td>
                    <td><?= tipoPagamentoLabel($p['tipo']) ?></td>
                    <td><?= moneyBr($p['valor']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($pagamentos)): ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Nenhum pagamento registrado ainda.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_cliente.php'; ?>
