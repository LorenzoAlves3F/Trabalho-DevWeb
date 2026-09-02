<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="print-only mb-3">
    <h2 class="brand-font">Carlore's - Relatório de Faturamento</h2>
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
        <label class="form-label small mb-0">Forma de pagamento</label>
        <select name="forma_pagamento" class="form-select form-select-sm">
            <option value="">Todas</option>
            <?php foreach (FORMAS_PAGAMENTO as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $formaPagamento === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-outline-carlores">Filtrar</button>
    </div>
    <div class="col-auto ms-auto d-flex gap-2">
        <button type="button" class="btn btn-sm btn-carlores" onclick="window.print()">Imprimir / Salvar PDF</button>
        <a class="btn btn-sm btn-outline-carlores" href="/admin/relatorios/faturamento_csv.php?<?= http_build_query($_GET) ?>">Exportar CSV</a>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-carlores dashboard-kpi">
            <div class="card-body">
                <div class="text-muted small">Total recebido no período</div>
                <div class="fs-3 fw-bold"><?= moneyBr($relatorio['total']) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card card-carlores mb-4">
    <div class="card-header">Subtotal por salão</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Salão</th><th>Qtd. pagamentos</th><th>Total recebido</th></tr></thead>
            <tbody>
            <?php foreach ($relatorio['subtotais'] as $s): ?>
                <tr>
                    <td><?= e($s['salao_nome']) ?></td>
                    <td><?= (int)$s['qtd_pagamentos'] ?></td>
                    <td><?= moneyBr($s['total_recebido']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($relatorio['subtotais'])): ?>
                <tr><td colspan="3" class="text-center text-muted py-3">Nenhum pagamento no período.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card card-carlores">
    <div class="card-header">Pagamentos no período</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Data</th><th>Reserva</th><th>Cliente</th><th>Salão</th><th>Forma</th><th>Tipo</th><th>Valor</th></tr></thead>
            <tbody>
            <?php foreach ($relatorio['linhas'] as $l): ?>
                <tr>
                    <td><?= dateBr($l['data_pagamento']) ?></td>
                    <td>#<?= (int)$l['reserva_id'] ?> (<?= dateBr($l['data_evento']) ?>)</td>
                    <td><?= e($l['cliente_nome']) ?></td>
                    <td><?= e($l['salao_nome']) ?></td>
                    <td><?= formaPagamentoLabel($l['forma_pagamento']) ?></td>
                    <td><?= tipoPagamentoLabel($l['tipo']) ?></td>
                    <td><?= moneyBr($l['valor']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($relatorio['linhas'])): ?>
                <tr><td colspan="7" class="text-center text-muted py-3">Nenhum pagamento no período.</td></tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="6" class="text-end">Total</td>
                    <td><?= moneyBr($relatorio['total']) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
