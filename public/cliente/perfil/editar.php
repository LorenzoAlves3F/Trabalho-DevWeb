<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirCliente();

$usuarioId = (int)$_SESSION['usuario_id'];
$clienteId = (int)$_SESSION['cliente_id'];

$usuario = Usuario::buscarPorId($usuarioId);
$cliente = Cliente::buscarPorId($clienteId);

$dados = [
    'nome'     => $usuario['nome'],
    'email'    => $usuario['email'],
    'telefone' => $cliente['telefone'],
    'endereco' => $cliente['endereco'],
];
$erros = [];
$mostrarSenha = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $acao = $_POST['acao'] ?? 'perfil';

    if ($acao === 'perfil') {
        $dados = array_merge($dados, $_POST);

        $erros = Validator::validate($_POST, [
            'nome'     => 'required|min:3|max:120',
            'email'    => 'required|email|max:150',
            'telefone' => 'required|telefone',
            'endereco' => 'required|max:255',
        ]);

        if (empty($erros['email']) && Usuario::emailExiste(trim($dados['email']), $usuarioId)) {
            $erros['email'] = 'Este e-mail já está em uso por outra conta.';
        }

        if (empty($erros)) {
            Usuario::atualizarPerfil($usuarioId, trim($dados['nome']), trim($dados['email']));
            Cliente::atualizar($clienteId, Validator::apenasDigitos($dados['telefone']), trim($dados['endereco']));
            $_SESSION['usuario_nome'] = trim($dados['nome']);
            flashSet('sucesso', 'Perfil atualizado com sucesso.');
            header('Location: /cliente/perfil/editar.php');
            exit;
        }
    } else {
        $mostrarSenha = true;

        $erros = Validator::validate($_POST, [
            'senha_atual'            => 'required',
            'nova_senha'             => 'required|senha_forte',
            'nova_senha_confirmacao' => 'required',
        ]);

        if (empty($erros['nova_senha_confirmacao']) && $_POST['nova_senha'] !== $_POST['nova_senha_confirmacao']) {
            $erros['nova_senha_confirmacao'] = 'As senhas não conferem.';
        }

        if (empty($erros) && !password_verify($_POST['senha_atual'], $usuario['senha_hash'])) {
            $erros['senha_atual'] = 'Senha atual incorreta.';
        }

        if (empty($erros)) {
            Usuario::atualizarSenha($usuarioId, password_hash($_POST['nova_senha'], PASSWORD_DEFAULT));
            flashSet('sucesso', 'Senha alterada com sucesso.');
            header('Location: /cliente/perfil/editar.php');
            exit;
        }
    }
}

$paginaAtual = 'perfil';
$tituloPagina = 'Meu Perfil';
require ROOT_PATH . '/views/cliente/perfil/editar.php';
