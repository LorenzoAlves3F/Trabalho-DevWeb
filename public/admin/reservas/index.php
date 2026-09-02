<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$filtros = [
    'status'      => $_GET['status'] ?? '',
    'salao_id'    => $_GET['salao_id'] ?? '',
    'data_inicio' => $_GET['data_inicio'] ?? '',
    'data_fim'    => $_GET['data_fim'] ?? '',
];

$reservas = Reserva::listar(array_filter($filtros));
$saloes = Salao::listarTodos();

$paginaAtual = 'reservas';
$tituloPagina = 'Reservas';
require ROOT_PATH . '/views/admin/reservas/listar.php';
