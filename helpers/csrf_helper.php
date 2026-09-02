<?php

declare(strict_types=1);

/** Gera (uma vez por sessao) e retorna o token CSRF atual. */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Campo hidden pronto para ser colocado dentro de todo <form method="post">. */
function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

/** Deve ser a primeira instrucao ao processar qualquer POST. Encerra a requisicao se o token nao bater. */
function csrfVerify(): void
{
    $tokenEnviado = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', is_string($tokenEnviado) ? $tokenEnviado : '')) {
        http_response_code(419);
        die('Token de seguranca invalido ou sessao expirada. Volte a pagina anterior e tente novamente.');
    }
}
