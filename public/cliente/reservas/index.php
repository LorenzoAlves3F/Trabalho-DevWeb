<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirCliente();

$reservas = Reserva::listarPorCliente((int)$_SESSION['cliente_id']);

$paginaAtual = 'reservas';
$tituloPagina = 'Minhas Reservas';
require ROOT_PATH . '/views/cliente/reservas/listar.php';
