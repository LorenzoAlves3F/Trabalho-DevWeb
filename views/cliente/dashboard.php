<?php require ROOT_PATH . '/views/partials/header_cliente.php'; ?>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card card-carlores dashboard-kpi h-100">
            <div class="card-body">
                <div class="text-muted small">Próximos eventos</div>
                <div class="fs-2 fw-bold"><?= count($proximasReservas) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-carlores dashboard-kpi h-100">
            <div class="card-body">
                <div class="text-muted small">Saldo devedor total</div>
                <div class="fs-2 fw-bold"><?= moneyBr($saldoTotalDevedor) ?></div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Minhas reservas</h2>
    <a href="/cliente/reservas/nova.php" class="btn btn-carlores btn-sm">+ Solicitar Reserva</a>
</div>
<div class="card card-carlores">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Data</th><th>Salão</th><th>Pacote</th><th>Status</th><th>Valor</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($reservas as $r): ?>
                <tr>
                    <td><?= dateBr($r['data_evento']) ?></td>
                    <td><?= e($r['salao_nome']) ?></td>
                    <td><?= e($r['pacote_nome']) ?></td>
                    <td><?= statusReservaBadge($r['status']) ?></td>
                    <td><?= moneyBr($r['valor_total']) ?></td>
                    <td class="text-end"><a href="/cliente/reservas/visualizar.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-carlores">Ver</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($reservas)): ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Você ainda não fez nenhuma reserva.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_cliente.php'; ?>
