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
$saloes = Salao::listarTodos();

$paginaAtual = 'faturamento';
$tituloPagina = 'Relatório de Faturamento';
require ROOT_PATH . '/views/admin/relatorios/faturamento.php';
