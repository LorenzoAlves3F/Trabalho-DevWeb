<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$dados = ['nome' => '', 'preco' => '', 'descricao' => ''];
$itens = [''];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);
    $itens = array_values(array_filter($_POST['itens'] ?? [], fn($i) => trim((string)$i) !== ''));

    $erros = Validator::validate($_POST, [
        'nome'  => 'required|max:100',
        'preco' => 'required|numeric|maior_que_zero',
    ]);

    if (empty($erros['nome']) && Pacote::nomeExiste(trim($dados['nome']))) {
        $erros['nome'] = 'Este nome de pacote já está em uso.';
    }

    if (empty($itens)) {
        $erros['itens'] = 'Adicione pelo menos um item ao pacote.';
    }

    if (empty($erros)) {
        $pdo = Database::conectar();
        $pdo->beginTransaction();
        try {
            $id = Pacote::criar(trim($dados['nome']), trim($dados['descricao'] ?? ''), (float)$dados['preco']);
            PacoteItem::substituirItens($id, $itens);
            $pdo->commit();
            flashSet('sucesso', 'Pacote cadastrado com sucesso.');
            header('Location: /admin/pacotes/index.php');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('[admin/pacotes/criar] ' . $e->getMessage());
            $erros['geral'] = 'Não foi possível salvar o pacote. Tente novamente.';
        }
    }
}

$modoEdicao = false;
$paginaAtual = 'pacotes';
$tituloPagina = 'Novo Pacote';
require ROOT_PATH . '/views/admin/pacotes/form.php';
