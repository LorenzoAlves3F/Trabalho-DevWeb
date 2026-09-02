<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$reserva = Reserva::buscarPorId($id);

if (!$reserva) {
    http_response_code(404);
    require ROOT_PATH . '/views/erros/404.php';
    exit;
}

$dados = $reserva;
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);

    $resultado = ReservaService::atualizar($id, $_POST);
    if ($resultado['sucesso']) {
        flashSet('sucesso', 'Reserva atualizada com sucesso.');
        header('Location: /admin/reservas/visualizar.php?id=' . $id);
        exit;
    }
    $erros = $resultado['erros'];
}

$saloes = Salao::listarAtivos();
if (!in_array((int)$reserva['salao_id'], array_column($saloes, 'id'), true)) {
    $salaoAtual = Salao::buscarPorId((int)$reserva['salao_id']);
    if ($salaoAtual) {
        $saloes[] = $salaoAtual;
    }
}

$pacotes = Pacote::listarAtivos();
if (!in_array((int)$reserva['pacote_id'], array_column($pacotes, 'id'), true)) {
    $pacoteAtual = Pacote::buscarPorId((int)$reserva['pacote_id']);
    if ($pacoteAtual) {
        $pacotes[] = $pacoteAtual;
    }
}

$modoEdicao = true;
$paginaAtual = 'reservas';
$tituloPagina = 'Editar Reserva #' . $id;
require ROOT_PATH . '/views/admin/reservas/form.php';
