<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
    <form method="get" class="row g-2">
        <div class="col-auto">
            <select name="status" class="form-select form-select-sm">
                <option value="">Todos os status</option>
                <?php foreach (STATUS_RESERVA as $valor => $rotulo): ?>
                    <option value="<?= e($valor) ?>" <?= $filtros['status'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <select name="salao_id" class="form-select form-select-sm">
                <option value="">Todos os salões</option>
                <?php foreach ($saloes as $s): ?>
                    <option value="<?= (int)$s['id'] ?>" <?= (string)$filtros['salao_id'] === (string)$s['id'] ? 'selected' : '' ?>><?= e($s['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <input type="date" name="data_inicio" class="form-control form-control-sm" value="<?= e($filtros['data_inicio']) ?>" title="De">
        </div>
        <div class="col-auto">
            <input type="date" name="data_fim" class="form-control form-control-sm" value="<?= e($filtros['data_fim']) ?>" title="Até">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-outline-carlores">Filtrar</button>
            <a href="/admin/reservas/index.php" class="btn btn-sm btn-outline-secondary">Limpar</a>
        </div>
    </form>
    <a href="/admin/reservas/criar.php" class="btn btn-carlores">+ Nova Reserva</a>
</div>
<div class="card card-carlores">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Data</th><th>Turno</th><th>Salão</th><th>Cliente</th><th>Pacote</th><th>Status</th><th>Valor</th><th class="text-end">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($reservas as $r): ?>
                <tr>
                    <td><?= dateBr($r['data_evento']) ?></td>
                    <td><?= turnoLabel($r['turno']) ?></td>
                    <td><?= e($r['salao_nome']) ?></td>
                    <td><?= e($r['cliente_nome']) ?></td>
                    <td><?= e($r['pacote_nome']) ?></td>
                    <td><?= statusReservaBadge($r['status']) ?></td>
                    <td><?= moneyBr($r['valor_total']) ?></td>
                    <td class="text-end">
                        <a href="/admin/reservas/visualizar.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-carlores">Ver</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($reservas)): ?>
                <tr><td colspan="8" class="text-center text-muted py-3">Nenhuma reserva encontrada.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
