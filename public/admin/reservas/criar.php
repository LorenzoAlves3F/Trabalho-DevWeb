<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$dados = [
    'cliente_id' => '', 'salao_id' => '', 'pacote_id' => '', 'data_evento' => '',
    'turno' => '', 'tipo_evento' => '', 'numero_convidados' => '', 'observacoes' => '',
];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);

    if (empty($dados['cliente_id'])) {
        $erros['cliente_id'] = 'Selecione um cliente.';
    }

    $resultado = ['sucesso' => false, 'erros' => [], 'id' => null];
    if (empty($erros)) {
        $resultado = ReservaService::criar($_POST);
    }

    if ($resultado['sucesso']) {
        flashSet('sucesso', 'Reserva criada com sucesso.');
        header('Location: /admin/reservas/visualizar.php?id=' . $resultado['id']);
        exit;
    }

    $erros = array_merge($erros, $resultado['erros']);
}

$clientes = Cliente::listarComUsuario();
$saloes = Salao::listarAtivos();
$pacotes = Pacote::listarAtivos();

$modoEdicao = false;
$paginaAtual = 'reservas';
$tituloPagina = 'Nova Reserva';
require ROOT_PATH . '/views/admin/reservas/form.php';
