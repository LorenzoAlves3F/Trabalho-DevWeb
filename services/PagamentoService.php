<?php

declare(strict_types=1);

class PagamentoService
{
    /** ATENCAO: reserva['valor_total'] vem do PDO como string - por isso o cast (float) abaixo. */
    public static function saldoDevedor(array $reserva): float
    {
        $totalPago = Pagamento::totalPagoPorReserva((int)$reserva['id']);
        return round((float)$reserva['valor_total'] - $totalPago, 2);
    }

    public static function statusPagamento(array $reserva): string
    {
        $total = (float)$reserva['valor_total'];
        $pago = Pagamento::totalPagoPorReserva((int)$reserva['id']);

        if ($pago <= 0) {
            return 'pendente';
        }

        return $pago < $total ? 'parcial' : 'pago';
    }

    /** @return array{sucesso:bool, erros:array<string,string>} */
    public static function registrar(array $reserva, array $dados): array
    {
        $erros = Validator::validate($dados, [
            'valor'           => 'required|numeric|maior_que_zero',
            'data_pagamento'  => 'required|date|data_nao_futura',
            'forma_pagamento' => 'required|in:dinheiro,pix,cartao_credito,cartao_debito,transferencia,boleto',
            'tipo'            => 'required|in:sinal,parcela,quitacao',
        ]);

        if ($reserva['status'] === 'cancelada') {
            $erros['geral'] = 'Não é possível registrar pagamento para uma reserva cancelada.';
        }

        if (empty($erros['valor'])) {
            $valor = (float)$dados['valor'];
            $saldo = self::saldoDevedor($reserva);
            if ($valor > $saldo) {
                $erros['valor'] = 'O valor informado (' . moneyBr($valor) . ') excede o saldo devedor da reserva (' . moneyBr($saldo) . ').';
            }
        }

        if (!empty($erros)) {
            return ['sucesso' => false, 'erros' => $erros];
        }

        Pagamento::criar([
            'reserva_id'      => $reserva['id'],
            'valor'           => (float)$dados['valor'],
            'data_pagamento'  => $dados['data_pagamento'],
            'forma_pagamento' => $dados['forma_pagamento'],
            'tipo'            => $dados['tipo'],
            'observacoes'     => trim($dados['observacoes'] ?? ''),
        ]);

        return ['sucesso' => true, 'erros' => []];
    }

    /** @return array{sucesso:bool, erros:array<string,string>} */
    public static function atualizar(array $reserva, int $pagamentoId, array $dados): array
    {
        $erros = Validator::validate($dados, [
            'valor'           => 'required|numeric|maior_que_zero',
            'data_pagamento'  => 'required|date|data_nao_futura',
            'forma_pagamento' => 'required|in:dinheiro,pix,cartao_credito,cartao_debito,transferencia,boleto',
            'tipo'            => 'required|in:sinal,parcela,quitacao',
        ]);

        if (empty($erros['valor'])) {
            // saldo devedor recalculado sem contar o proprio pagamento que esta sendo editado
            $pagamentoAtual = Pagamento::buscarPorId($pagamentoId);
            $saldoSemEste = self::saldoDevedor($reserva) + (float)($pagamentoAtual['valor'] ?? 0);
            $valor = (float)$dados['valor'];
            if ($valor > $saldoSemEste) {
                $erros['valor'] = 'O valor informado (' . moneyBr($valor) . ') excede o saldo devedor da reserva (' . moneyBr($saldoSemEste) . ').';
            }
        }

        if (!empty($erros)) {
            return ['sucesso' => false, 'erros' => $erros];
        }

        Pagamento::atualizar($pagamentoId, [
            'valor'           => (float)$dados['valor'],
            'data_pagamento'  => $dados['data_pagamento'],
            'forma_pagamento' => $dados['forma_pagamento'],
            'tipo'            => $dados['tipo'],
            'observacoes'     => trim($dados['observacoes'] ?? ''),
        ]);

        return ['sucesso' => true, 'erros' => []];
    }
}
