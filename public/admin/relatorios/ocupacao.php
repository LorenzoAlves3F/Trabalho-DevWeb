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
$saloes = Salao::listarTodos();

$paginaAtual = 'ocupacao';
$tituloPagina = 'Agenda de Ocupação dos Salões';
require ROOT_PATH . '/views/admin/relatorios/ocupacao.php';
