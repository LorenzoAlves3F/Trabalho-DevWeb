<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$dados = ['nome' => '', 'email' => '', 'telefone' => '', 'cpf' => '', 'endereco' => ''];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);

    $resultado = AuthService::registrarCliente($_POST);
    if ($resultado['sucesso']) {
        flashSet('sucesso', 'Cliente cadastrado com sucesso.');
        header('Location: /admin/clientes/index.php');
        exit;
    }
    $erros = $resultado['erros'];
}

$modoEdicao = false;
$paginaAtual = 'clientes';
$tituloPagina = 'Novo Cliente';
require ROOT_PATH . '/views/admin/clientes/form.php';
