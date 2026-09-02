<?php

declare(strict_types=1);

/** Guarda uma mensagem para ser exibida apos o proximo redirect (padrao Post-Redirect-Get). */
function flashSet(string $tipo, string $mensagem): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensagem' => $mensagem];
}

function flashGet(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

/** HTML pronto (ja escapado) do alerta Bootstrap, ou string vazia se nao houver flash. */
function flashRender(): string
{
    $flash = flashGet();
    if (!$flash) {
        return '';
    }

    $cor = match ($flash['tipo']) {
        'sucesso' => 'success',
        'erro'    => 'danger',
        'aviso'   => 'warning',
        default   => 'info',
    };

    return '<div class="alert alert-' . $cor . ' alert-dismissible fade show" role="alert">'
        . e($flash['mensagem'])
        . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>'
        . '</div>';
}
