<?php

declare(strict_types=1);

/**
 * Escapa uma string para saida segura em HTML (mitigacao de XSS).
 * Deve envolver TODO dado dinamico impresso nas views.
 */
function e(mixed $valor): string
{
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

/** Escapa e converte quebras de linha em <br> (para campos de texto livre, ex.: observacoes). */
function textoMultilinha(?string $texto): string
{
    return nl2br(e($texto));
}

function moneyBr(float|string|null $valor): string
{
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}

function dateBr(?string $dataIso): string
{
    if (empty($dataIso)) {
        return '';
    }
    $timestamp = strtotime($dataIso);
    return $timestamp ? date('d/m/Y', $timestamp) : '';
}

function dateTimeBr(?string $dataIso): string
{
    if (empty($dataIso)) {
        return '';
    }
    $timestamp = strtotime($dataIso);
    return $timestamp ? date('d/m/Y H:i', $timestamp) : '';
}

function cpfFormatado(?string $cpf): string
{
    $digitos = preg_replace('/\D/', '', (string)$cpf);
    if (strlen($digitos) !== 11) {
        return (string)$cpf;
    }
    return substr($digitos, 0, 3) . '.' . substr($digitos, 3, 3) . '.' . substr($digitos, 6, 3) . '-' . substr($digitos, 9, 2);
}

function telefoneFormatado(?string $telefone): string
{
    $digitos = preg_replace('/\D/', '', (string)$telefone);
    if (strlen($digitos) === 11) {
        return '(' . substr($digitos, 0, 2) . ') ' . substr($digitos, 2, 5) . '-' . substr($digitos, 7);
    }
    if (strlen($digitos) === 10) {
        return '(' . substr($digitos, 0, 2) . ') ' . substr($digitos, 2, 4) . '-' . substr($digitos, 6);
    }
    return (string)$telefone;
}

const TURNOS = ['manha' => 'Manha', 'tarde' => 'Tarde', 'noite' => 'Noite'];
const STATUS_RESERVA = ['solicitada' => 'Solicitada', 'confirmada' => 'Confirmada', 'cancelada' => 'Cancelada'];
const FORMAS_PAGAMENTO = [
    'dinheiro'        => 'Dinheiro',
    'pix'             => 'Pix',
    'cartao_credito'  => 'Cartao de Credito',
    'cartao_debito'   => 'Cartao de Debito',
    'transferencia'   => 'Transferencia',
    'boleto'          => 'Boleto',
];
const TIPOS_PAGAMENTO = ['sinal' => 'Sinal', 'parcela' => 'Parcela', 'quitacao' => 'Quitacao'];

function turnoLabel(?string $turno): string
{
    return TURNOS[$turno] ?? e($turno);
}

function statusReservaLabel(?string $status): string
{
    return STATUS_RESERVA[$status] ?? e($status);
}

function statusReservaBadge(?string $status): string
{
    $mapa = ['solicitada' => 'warning', 'confirmada' => 'success', 'cancelada' => 'secondary'];
    $cor = $mapa[$status] ?? 'secondary';
    return '<span class="badge text-bg-' . $cor . '">' . statusReservaLabel($status) . '</span>';
}

function formaPagamentoLabel(?string $forma): string
{
    return FORMAS_PAGAMENTO[$forma] ?? e($forma);
}

function tipoPagamentoLabel(?string $tipo): string
{
    return TIPOS_PAGAMENTO[$tipo] ?? e($tipo);
}

function statusPagamentoBadge(string $status): string
{
    $mapa = ['pendente' => 'danger', 'parcial' => 'warning', 'pago' => 'success'];
    $labels = ['pendente' => 'Pendente', 'parcial' => 'Parcial', 'pago' => 'Pago'];
    $cor = $mapa[$status] ?? 'secondary';
    return '<span class="badge text-bg-' . $cor . '">' . ($labels[$status] ?? e($status)) . '</span>';
}
