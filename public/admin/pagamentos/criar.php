<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$reservaId = (int)($_GET['reserva_id'] ?? $_POST['reserva_id'] ?? 0);
$reserva = $reservaId ? Reserva::buscarPorId($reservaId) : null;

if (!$reserva) {
    http_response_code(404);
    require ROOT_PATH . '/views/erros/404.php';
    exit;
}

$dados = ['valor' => '', 'data_pagamento' => date('Y-m-d'), 'forma_pagamento' => '', 'tipo' => 'parcela', 'observacoes' => ''];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);

    $resultado = PagamentoService::registrar($reserva, $_POST);
    if ($resultado['sucesso']) {
        flashSet('sucesso', 'Pagamento registrado com sucesso.');
        header('Location: /admin/reservas/visualizar.php?id=' . $reservaId);
        exit;
    }
    $erros = $resultado['erros'];
}

$saldoDevedor = PagamentoService::saldoDevedor($reserva);

$modoEdicao = false;
$paginaAtual = 'reservas';
$tituloPagina = 'Registrar Pagamento - Reserva #' . $reservaId;
require ROOT_PATH . '/views/admin/pagamentos/form.php';
