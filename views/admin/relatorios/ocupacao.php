<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="print-only mb-3">
    <h2 class="brand-font">Carlore's - Agenda de Ocupação dos Salões</h2>
    <p>Período: <?= dateBr($relatorio['inicio']) ?> a <?= dateBr($relatorio['fim']) ?> - Emitido em <?= dateTimeBr(date('Y-m-d H:i:s')) ?></p>
</div>

<form method="get" class="row g-2 align-items-end mb-3 no-print">
    <div class="col-auto">
        <label class="form-label small mb-0">De</label>
        <input type="date" name="data_inicio" class="form-control form-control-sm" value="<?= e($relatorio['inicio']) ?>">
    </div>
    <div class="col-auto">
        <label class="form-label small mb-0">Até</label>
        <input type="date" name="data_fim" class="form-control form-control-sm" value="<?= e($relatorio['fim']) ?>">
    </div>
    <div class="col-auto">
        <label class="form-label small mb-0">Salão</label>
        <select name="salao_id" class="form-select form-select-sm">
            <option value="">Todos</option>
            <?php foreach ($saloes as $s): ?>
                <option value="<?= (int)$s['id'] ?>" <?= (string)$salaoId === (string)$s['id'] ? 'selected' : '' ?>><?= e($s['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <label class="form-label small mb-0">Status</label>
        <select name="status" class="form-select form-select-sm">
            <option value="">Ativas (não canceladas)</option>
            <?php foreach (STATUS_RESERVA as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $status === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-outline-carlores">Filtrar</button>
    </div>
    <div class="col-auto ms-auto d-flex gap-2">
        <button type="button" class="btn btn-sm btn-carlores" onclick="window.print()">Imprimir / Salvar PDF</button>
        <a class="btn btn-sm btn-outline-carlores" href="/admin/relatorios/ocupacao_csv.php?<?= http_build_query($_GET) ?>">Exportar CSV</a>
    </div>
</form>

<div class="card card-carlores">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Data</th><th>Turno</th><th>Salão</th><th>Cliente</th><th>Tipo de Evento</th><th>Convidados</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($relatorio['linhas'] as $l): ?>
                <tr>
                    <td><?= dateBr($l['data_evento']) ?></td>
                    <td><?= turnoLabel($l['turno']) ?></td>
                    <td><?= e($l['salao_nome']) ?></td>
                    <td><?= e($l['cliente_nome']) ?></td>
                    <td><?= e($l['tipo_evento']) ?></td>
                    <td><?= (int)$l['numero_convidados'] ?></td>
                    <td><?= statusReservaBadge($l['status']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($relatorio['linhas'])): ?>
                <tr><td colspan="7" class="text-center text-muted py-3">Nenhuma reserva no período.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
