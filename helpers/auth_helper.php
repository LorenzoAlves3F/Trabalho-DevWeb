<?php

declare(strict_types=1);

function usuarioLogado(): ?array
{
    if (empty($_SESSION['usuario_id'])) {
        return null;
    }

    return [
        'id'         => (int)$_SESSION['usuario_id'],
        'nome'       => $_SESSION['usuario_nome'],
        'tipo'       => $_SESSION['usuario_tipo'],
        'cliente_id' => isset($_SESSION['cliente_id']) ? (int)$_SESSION['cliente_id'] : null,
    ];
}

function estaLogado(): bool
{
    return !empty($_SESSION['usuario_id']);
}

function ehAdmin(): bool
{
    return estaLogado() && $_SESSION['usuario_tipo'] === 'admin';
}

function ehCliente(): bool
{
    return estaLogado() && $_SESSION['usuario_tipo'] === 'cliente';
}

/** Exige apenas que exista uma sessao ativa (qualquer papel). Redireciona ao login caso contrario. */
function exigirLogin(): void
{
    if (!estaLogado()) {
        header('Location: /auth/login.php');
        exit;
    }
}

function exigirAdmin(): void
{
    exigirLogin();
    if (!ehAdmin()) {
        http_response_code(403);
        require ROOT_PATH . '/views/erros/403.php';
        exit;
    }
}

function exigirCliente(): void
{
    exigirLogin();
    if (!ehCliente()) {
        http_response_code(403);
        require ROOT_PATH . '/views/erros/403.php';
        exit;
    }
}

/** Deve ser chamada logo apos validar as credenciais no login (regenera o ID de sessao contra session fixation). */
function iniciarSessaoUsuario(array $usuario, ?int $clienteId = null): void
{
    session_regenerate_id(true);
    $_SESSION['usuario_id']   = (int)$usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_tipo'] = $usuario['tipo'];
    if ($clienteId !== null) {
        $_SESSION['cliente_id'] = $clienteId;
    }
}

function encerrarSessao(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $parametros = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $parametros['path'],
            $parametros['domain'],
            $parametros['secure'],
            $parametros['httponly']
        );
    }

    session_unset();
    session_destroy();
}
