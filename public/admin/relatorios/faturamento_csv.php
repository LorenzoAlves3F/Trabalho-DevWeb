<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$padrao = RelatorioService::periodoPadrao();
$dataInicio = $_GET['data_inicio'] ?? $padrao['inicio'];
$dataFim = $_GET['data_fim'] ?? $padrao['fim'];
$salaoId = !empty($_GET['salao_id']) ? (int)$_GET['salao_id'] : null;
$formaPagamento = $_GET['forma_pagamento'] ?? '';

$relatorio = RelatorioService::faturamentoPorPeriodo($dataInicio, $dataFim, $salaoId, $formaPagamento ?: null);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="faturamento_' . $dataInicio . '_a_' . $dataFim . '.csv"');

$saida = fopen('php://output', 'w');
fputs($saida, "\xEF\xBB\xBF"); // BOM UTF-8 para a acentuacao abrir corretamente no Excel
fputcsv($saida, ['Data Pagamento', 'Reserva', 'Data Evento', 'Cliente', 'Salao', 'Forma de Pagamento', 'Tipo', 'Valor (R$)'], ';');

foreach ($relatorio['linhas'] as $l) {
    fputcsv($saida, [
        dateBr($l['data_pagamento']),
        '#' . $l['reserva_id'],
        dateBr($l['data_evento']),
        $l['cliente_nome'],
        $l['salao_nome'],
        formaPagamentoLabel($l['forma_pagamento']),
        tipoPagamentoLabel($l['tipo']),
        number_format((float)$l['valor'], 2, ',', '.'),
    ], ';');
}

fputcsv($saida, ['', '', '', '', '', '', 'TOTAL', number_format($relatorio['total'], 2, ',', '.')], ';');
fclose($saida);
exit;
