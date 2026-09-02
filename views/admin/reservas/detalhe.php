<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-carlores mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Detalhes da reserva</span>
                <?= statusReservaBadge($reserva['status']) ?>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Cliente</dt><dd class="col-sm-8"><?= e($reserva['cliente_nome']) ?> (<?= e($reserva['cliente_email']) ?>)</dd>
                    <dt class="col-sm-4">Salão</dt><dd class="col-sm-8"><?= e($reserva['salao_nome']) ?> (capacidade <?= (int)$reserva['salao_capacidade'] ?>)</dd>
                    <dt class="col-sm-4">Data / turno</dt><dd class="col-sm-8"><?= dateBr($reserva['data_evento']) ?> - <?= turnoLabel($reserva['turno']) ?></dd>
                    <dt class="col-sm-4">Tipo de evento</dt><dd class="col-sm-8"><?= e($reserva['tipo_evento']) ?></dd>
                    <dt class="col-sm-4">Convidados</dt><dd class="col-sm-8"><?= (int)$reserva['numero_convidados'] ?></dd>
                    <dt class="col-sm-4">Pacote</dt>
                    <dd class="col-sm-8">
                        <?= e($reserva['pacote_nome']) ?>
                        <ul class="small mb-0">
                            <?php foreach ($itensPacote as $item): ?><li><?= e($item['descricao_item']) ?></li><?php endforeach; ?>
                        </ul>
                    </dd>
                    <dt class="col-sm-4">Valor salão</dt><dd class="col-sm-8"><?= moneyBr($reserva['valor_salao']) ?></dd>
                    <dt class="col-sm-4">Valor pacote</dt><dd class="col-sm-8"><?= moneyBr($reserva['valor_pacote']) ?></dd>
                    <dt class="col-sm-4">Desconto</dt><dd class="col-sm-8"><?= moneyBr($reserva['desconto']) ?></dd>
                    <dt class="col-sm-4">Valor total</dt><dd class="col-sm-8 fw-bold"><?= moneyBr($reserva['valor_total']) ?></dd>
                    <dt class="col-sm-4">Observações</dt><dd class="col-sm-8"><?= $reserva['observacoes'] ? textoMultilinha($reserva['observacoes']) : '-' ?></dd>
                </dl>
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="/admin/reservas/editar.php?id=<?= (int)$reserva['id'] ?>" class="btn btn-sm btn-outline-carlores">Editar</a>
                <?php if ($reserva['status'] === 'solicitada'): ?>
                    <form method="post" action="/admin/reservas/confirmar.php">
                        <?= csrfField() ?>
                        <input type="hidden" name="id" value="<?= (int)$reserva['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-success">Confirmar reserva</button>
                    </form>
                <?php endif; ?>
                <?php if ($reserva['status'] !== 'cancelada'): ?>
                    <form method="post" action="/admin/reservas/cancelar.php" onsubmit="return confirm('Cancelar esta reserva?');">
                        <?= csrfField() ?>
                        <input type="hidden" name="id" value="<?= (int)$reserva['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar reserva</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card card-carlores">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Pagamentos</span>
                <?= statusPagamentoBadge($statusPagamento) ?>
            </div>
            <div class="card-body">
                <p class="mb-2">Saldo devedor: <strong><?= moneyBr($saldoDevedor) ?></strong></p>
                <?php if ($reserva['status'] !== 'cancelada' && $saldoDevedor > 0): ?>
                    <a href="/admin/pagamentos/criar.php?reserva_id=<?= (int)$reserva['id'] ?>" class="btn btn-sm btn-carlores mb-3">+ Registrar pagamento</a>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Data</th><th>Forma</th><th>Tipo</th><th>Valor</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($pagamentos as $p): ?>
                            <tr>
                                <td><?= dateBr($p['data_pagamento']) ?></td>
                                <td><?= formaPagamentoLabel($p['forma_pagamento']) ?></td>
                                <td><?= tipoPagamentoLabel($p['tipo']) ?></td>
                                <td><?= moneyBr($p['valor']) ?></td>
                                <td class="text-end"><a href="/admin/pagamentos/editar.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-outline-carlores">Editar</a></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($pagamentos)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3">Nenhum pagamento registrado.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
