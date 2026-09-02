<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$clienteId = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$cliente = Cliente::buscarPorId($clienteId);

if (!$cliente) {
    http_response_code(404);
    require ROOT_PATH . '/views/erros/404.php';
    exit;
}

$dados = $cliente;
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);

    $erros = Validator::validate($_POST, [
        'nome'     => 'required|min:3|max:120',
        'email'    => 'required|email|max:150',
        'telefone' => 'required|telefone',
        'cpf'      => 'required|cpf',
        'endereco' => 'required|max:255',
    ]);

    if (empty($erros['email']) && Usuario::emailExiste(trim($dados['email']), (int)$cliente['usuario_id'])) {
        $erros['email'] = 'Este e-mail já está cadastrado para outro usuário.';
    }

    $cpfDigitos = Validator::apenasDigitos($dados['cpf'] ?? '');
    if (empty($erros['cpf']) && Cliente::cpfExiste($cpfDigitos, (int)$cliente['id'])) {
        $erros['cpf'] = 'Este CPF já está cadastrado para outro cliente.';
    }

    if (empty($erros)) {
        Usuario::atualizarPerfil((int)$cliente['usuario_id'], trim($dados['nome']), trim($dados['email']));
        Cliente::atualizarCompleto(
            (int)$cliente['id'],
            Validator::apenasDigitos($dados['telefone']),
            $cpfDigitos,
            trim($dados['endereco'])
        );
        flashSet('sucesso', 'Cliente atualizado com sucesso.');
        header('Location: /admin/clientes/index.php');
        exit;
    }
}

$modoEdicao = true;
$paginaAtual = 'clientes';
$tituloPagina = 'Editar Cliente';
require ROOT_PATH . '/views/admin/clientes/form.php';
