<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$padrao = RelatorioService::periodoPadrao();
$dataInicio = $_GET['data_inicio'] ?? $padrao['inicio'];
$dataFim = $_GET['data_fim'] ?? $padrao['fim'];
$salaoId = !empty($_GET['salao_id']) ? (int)$_GET['salao_id'] : null;
$status = $_GET['status'] ?? '';

$relatorio = RelatorioService::ocupacaoSaloes($dataInicio, $dataFim, $salaoId, $status ?: null);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="ocupacao_' . $dataInicio . '_a_' . $dataFim . '.csv"');

$saida = fopen('php://output', 'w');
fputs($saida, "\xEF\xBB\xBF");
fputcsv($saida, ['Data', 'Turno', 'Salao', 'Cliente', 'Tipo de Evento', 'Convidados', 'Status'], ';');

foreach ($relatorio['linhas'] as $l) {
    fputcsv($saida, [
        dateBr($l['data_evento']),
        turnoLabel($l['turno']),
        $l['salao_nome'],
        $l['cliente_nome'],
        $l['tipo_evento'],
        $l['numero_convidados'],
        statusReservaLabel($l['status']),
    ], ';');
}

fclose($saida);
exit;
