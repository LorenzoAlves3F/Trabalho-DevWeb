<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$clienteId = (int)($_GET['id'] ?? 0);
$cliente = Cliente::buscarPorId($clienteId);

if (!$cliente) {
    http_response_code(404);
    require ROOT_PATH . '/views/erros/404.php';
    exit;
}

$reservas = Reserva::listarPorCliente($clienteId);

$paginaAtual = 'clientes';
$tituloPagina = 'Cliente: ' . $cliente['nome'];
require ROOT_PATH . '/views/admin/clientes/detalhe.php';
