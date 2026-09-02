<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';
exigirAdmin();

$inicioMes = date('Y-m-01');
$fimMes = date('Y-m-t');

$reservasMes = Reserva::listar(['data_inicio' => $inicioMes, 'data_fim' => $fimMes]);
$faturamentoMes = RelatorioService::faturamentoPorPeriodo($inicioMes, $fimMes, null, null);
$solicitacoesPendentes = Reserva::listar(['status' => 'solicitada']);

$proximasReservas = Reserva::listar(['data_inicio' => date('Y-m-d'), 'status' => 'confirmada']);
$proximasReservas = array_slice($proximasReservas, 0, 5);

$paginaAtual = 'dashboard';
$tituloPagina = 'Dashboard';
require ROOT_PATH . '/views/partials/header_admin.php';
?>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-carlores dashboard-kpi h-100">
            <div class="card-body">
                <div class="text-muted small">Reservas neste mês</div>
                <div class="fs-2 fw-bold"><?= count($reservasMes) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-carlores dashboard-kpi h-100">
            <div class="card-body">
                <div class="text-muted small">Faturamento recebido neste mês</div>
                <div class="fs-2 fw-bold"><?= moneyBr($faturamentoMes['total']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-carlores dashboard-kpi h-100">
            <div class="card-body">
                <div class="text-muted small">Solicitações aguardando confirmação</div>
                <div class="fs-2 fw-bold"><?= count($solicitacoesPendentes) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card card-carlores">
    <div class="card-header">Próximos eventos confirmados</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead><tr><th>Data</th><th>Turno</th><th>Salão</th><th>Cliente</th><th>Tipo</th></tr></thead>
                <tbody>
                <?php if (empty($proximasReservas)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Nenhum evento confirmado a partir de hoje.</td></tr>
                <?php endif; ?>
                <?php foreach ($proximasReservas as $r): ?>
                    <tr>
                        <td><?= dateBr($r['data_evento']) ?></td>
                        <td><?= turnoLabel($r['turno']) ?></td>
                        <td><?= e($r['salao_nome']) ?></td>
                        <td><?= e($r['cliente_nome']) ?></td>
                        <td><?= e($r['tipo_evento']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
