<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirCliente();

$dados = [
    'salao_id' => '', 'pacote_id' => '', 'data_evento' => '',
    'turno' => '', 'tipo_evento' => '', 'numero_convidados' => '', 'observacoes' => '',
];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);

    $payload = $_POST;
    $payload['cliente_id'] = $_SESSION['cliente_id'];

    $resultado = ReservaService::criar($payload);
    if ($resultado['sucesso']) {
        flashSet('sucesso', 'Solicitação de reserva enviada! Em breve nossa equipe irá confirmar.');
        header('Location: /cliente/reservas/visualizar.php?id=' . $resultado['id']);
        exit;
    }
    $erros = $resultado['erros'];
}

$saloes = Salao::listarAtivos();
$pacotes = Pacote::listarAtivos();

$paginaAtual = 'nova';
$tituloPagina = 'Solicitar Reserva';
require ROOT_PATH . '/views/cliente/reservas/nova.php';
