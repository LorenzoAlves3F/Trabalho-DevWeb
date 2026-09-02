<?php require ROOT_PATH . '/views/partials/header_cliente.php'; ?>
<div class="d-flex justify-content-end mb-3">
    <a href="/cliente/reservas/nova.php" class="btn btn-carlores">+ Solicitar Reserva</a>
</div>
<div class="card card-carlores">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Data</th><th>Turno</th><th>Salão</th><th>Pacote</th><th>Status</th><th>Valor</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($reservas as $r): ?>
                <tr>
                    <td><?= dateBr($r['data_evento']) ?></td>
                    <td><?= turnoLabel($r['turno']) ?></td>
                    <td><?= e($r['salao_nome']) ?></td>
                    <td><?= e($r['pacote_nome']) ?></td>
                    <td><?= statusReservaBadge($r['status']) ?></td>
                    <td><?= moneyBr($r['valor_total']) ?></td>
                    <td class="text-end"><a href="/cliente/reservas/visualizar.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-carlores">Ver</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($reservas)): ?>
                <tr><td colspan="7" class="text-center text-muted py-3">Você ainda não fez nenhuma reserva.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_cliente.php'; ?>
