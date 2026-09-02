<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$dados = ['nome' => '', 'capacidade' => '', 'descricao' => '', 'valor_base' => '', 'foto' => null];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);

    $erros = Validator::validate($_POST, [
        'nome'       => 'required|max:100',
        'capacidade' => 'required|integer|maior_que_zero',
        'valor_base' => 'required|numeric|maior_que_zero',
    ]);

    if (empty($erros['nome']) && Salao::nomeExiste(trim($dados['nome']))) {
        $erros['nome'] = 'Este nome de salão já está em uso.';
    }

    $uploadResultado = ['sucesso' => true];
    if (!empty($_FILES['foto']['name'])) {
        $uploadResultado = UploadService::salvarFotoSalao($_FILES['foto']);
        if (!$uploadResultado['sucesso']) {
            $erros['foto'] = $uploadResultado['erro'];
        }
    }

    if (empty($erros)) {
        Salao::criar([
            'nome'       => trim($dados['nome']),
            'capacidade' => (int)$dados['capacidade'],
            'descricao'  => trim($dados['descricao'] ?? ''),
            'valor_base' => (float)$dados['valor_base'],
            'foto'       => $uploadResultado['nome_arquivo'] ?? null,
        ]);
        flashSet('sucesso', 'Salão cadastrado com sucesso.');
        header('Location: /admin/saloes/index.php');
        exit;
    }
}

$modoEdicao = false;
$paginaAtual = 'saloes';
$tituloPagina = 'Novo Salão';
require ROOT_PATH . '/views/admin/saloes/form.php';
