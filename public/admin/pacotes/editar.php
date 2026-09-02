<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$pacote = Pacote::buscarPorId($id);

if (!$pacote) {
    http_response_code(404);
    require ROOT_PATH . '/views/erros/404.php';
    exit;
}

$dados = $pacote;
$itens = array_map(fn($i) => $i['descricao_item'], PacoteItem::listarPorPacote($id));
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);
    $itens = array_values(array_filter($_POST['itens'] ?? [], fn($i) => trim((string)$i) !== ''));

    $erros = Validator::validate($_POST, [
        'nome'  => 'required|max:100',
        'preco' => 'required|numeric|maior_que_zero',
    ]);

    if (empty($erros['nome']) && Pacote::nomeExiste(trim($dados['nome']), $id)) {
        $erros['nome'] = 'Este nome de pacote já está em uso.';
    }

    if (empty($itens)) {
        $erros['itens'] = 'Adicione pelo menos um item ao pacote.';
    }

    if (empty($erros)) {
        $pdo = Database::conectar();
        $pdo->beginTransaction();
        try {
            Pacote::atualizar($id, trim($dados['nome']), trim($dados['descricao'] ?? ''), (float)$dados['preco']);
            PacoteItem::substituirItens($id, $itens);
            $pdo->commit();
            flashSet('sucesso', 'Pacote atualizado com sucesso.');
            header('Location: /admin/pacotes/index.php');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('[admin/pacotes/editar] ' . $e->getMessage());
            $erros['geral'] = 'Não foi possível salvar o pacote. Tente novamente.';
        }
    }
}

$modoEdicao = true;
$paginaAtual = 'pacotes';
$tituloPagina = 'Editar Pacote';
require ROOT_PATH . '/views/admin/pacotes/form.php';
