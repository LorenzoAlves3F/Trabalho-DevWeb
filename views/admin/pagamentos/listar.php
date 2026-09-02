<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="card card-carlores">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Data</th><th>Reserva</th><th>Cliente</th><th>Salão</th><th>Forma</th><th>Tipo</th><th>Valor</th><th class="text-end">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($pagamentos as $p): ?>
                <tr>
                    <td><?= dateBr($p['data_pagamento']) ?></td>
                    <td><a href="/admin/reservas/visualizar.php?id=<?= (int)$p['reserva_id'] ?>">#<?= (int)$p['reserva_id'] ?> (<?= dateBr($p['data_evento']) ?>)</a></td>
                    <td><?= e($p['cliente_nome']) ?></td>
                    <td><?= e($p['salao_nome']) ?></td>
                    <td><?= formaPagamentoLabel($p['forma_pagamento']) ?></td>
                    <td><?= tipoPagamentoLabel($p['tipo']) ?></td>
                    <td><?= moneyBr($p['valor']) ?></td>
                    <td class="text-end">
                        <a href="/admin/pagamentos/editar.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-outline-carlores">Editar</a>
                        <form method="post" action="/admin/pagamentos/excluir.php" class="d-inline" onsubmit="return confirm('Excluir este pagamento?');">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($pagamentos)): ?>
                <tr><td colspan="8" class="text-center text-muted py-3">Nenhum pagamento registrado.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
