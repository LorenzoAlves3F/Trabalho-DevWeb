<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';
exigirCliente();

$clienteId = (int)$_SESSION['cliente_id'];
$reservas = Reserva::listarPorCliente($clienteId);

$proximasReservas = array_filter(
    $reservas,
    fn($r) => $r['status'] !== 'cancelada' && $r['data_evento'] >= date('Y-m-d')
);

$saldoTotalDevedor = 0.0;
foreach ($reservas as $r) {
    if ($r['status'] !== 'cancelada') {
        $saldoTotalDevedor += PagamentoService::saldoDevedor($r);
    }
}

$paginaAtual = 'dashboard';
$tituloPagina = 'Minha Área';
require ROOT_PATH . '/views/cliente/dashboard.php';
