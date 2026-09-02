<?php

declare(strict_types=1);

class ReservaService
{
    /**
     * Valida e cria uma solicitacao de reserva (status inicial = solicitada).
     * @return array{sucesso:bool, erros:array<string,string>, id?:int}
     */
    public static function criar(array $dados): array
    {
        $erros = self::validarDadosBasicos($dados);

        $salao = Salao::buscarPorId((int)($dados['salao_id'] ?? 0));
        $pacote = Pacote::buscarPorId((int)($dados['pacote_id'] ?? 0));

        if (!$salao || !$salao['ativo']) {
            $erros['salao_id'] = 'Selecione um salão válido.';
        }
        if (!$pacote || !$pacote['ativo']) {
            $erros['pacote_id'] = 'Selecione um pacote válido.';
        }

        if ($salao && empty($erros['numero_convidados'])) {
            $convidados = (int)($dados['numero_convidados'] ?? 0);
            if ($convidados > (int)$salao['capacidade']) {
                $erros['numero_convidados'] = "O número de convidados ({$convidados}) excede a capacidade do salão selecionado ({$salao['capacidade']}).";
            }
        }

        if ($salao && empty($erros['data_evento']) && Reserva::existeConflito((int)$salao['id'], $dados['data_evento'])) {
            $erros['data_evento'] = 'Este salão já está reservado para a data selecionada.';
        }

        if (!empty($erros)) {
            return ['sucesso' => false, 'erros' => $erros];
        }

        try {
            $id = Reserva::criar([
                'cliente_id'        => $dados['cliente_id'],
                'salao_id'          => $salao['id'],
                'pacote_id'         => $pacote['id'],
                'data_evento'       => $dados['data_evento'],
                'turno'             => $dados['turno'],
                'tipo_evento'       => trim($dados['tipo_evento']),
                'numero_convidados' => (int)$dados['numero_convidados'],
                'status'            => 'solicitada',
                'valor_salao'       => $salao['valor_base'],
                'valor_pacote'      => $pacote['preco'],
                'desconto'          => 0,
                'observacoes'       => trim($dados['observacoes'] ?? ''),
            ]);

            return ['sucesso' => true, 'erros' => [], 'id' => $id];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return ['sucesso' => false, 'erros' => ['data_evento' => 'Este salão já está reservado para a data selecionada.']];
            }
            error_log('[ReservaService::criar] ' . $e->getMessage());
            return ['sucesso' => false, 'erros' => ['geral' => 'Não foi possível concluir a reserva. Tente novamente.']];
        }
    }

    /**
     * Atualizacao pelo admin (pode reajustar salao/pacote/data/desconto).
     * @return array{sucesso:bool, erros:array<string,string>}
     */
    public static function atualizar(int $id, array $dados): array
    {
        $erros = self::validarDadosBasicos($dados);

        $salao = Salao::buscarPorId((int)($dados['salao_id'] ?? 0));
        $pacote = Pacote::buscarPorId((int)($dados['pacote_id'] ?? 0));

        if (!$salao) {
            $erros['salao_id'] = 'Selecione um salão válido.';
        }
        if (!$pacote) {
            $erros['pacote_id'] = 'Selecione um pacote válido.';
        }

        if ($salao && empty($erros['numero_convidados'])) {
            $convidados = (int)($dados['numero_convidados'] ?? 0);
            if ($convidados > (int)$salao['capacidade']) {
                $erros['numero_convidados'] = "O número de convidados ({$convidados}) excede a capacidade do salão selecionado ({$salao['capacidade']}).";
            }
        }

        if ($salao && empty($erros['data_evento']) && Reserva::existeConflito((int)$salao['id'], $dados['data_evento'], $id)) {
            $erros['data_evento'] = 'Este salão já está reservado para a data selecionada.';
        }

        $desconto = (float)($dados['desconto'] ?? 0);
        if ($desconto < 0) {
            $erros['desconto'] = 'O desconto não pode ser negativo.';
        }

        if (!empty($erros)) {
            return ['sucesso' => false, 'erros' => $erros];
        }

        try {
            Reserva::atualizar($id, [
                'salao_id'          => $salao['id'],
                'pacote_id'         => $pacote['id'],
                'data_evento'       => $dados['data_evento'],
                'turno'             => $dados['turno'],
                'tipo_evento'       => trim($dados['tipo_evento']),
                'numero_convidados' => (int)$dados['numero_convidados'],
                'valor_salao'       => $salao['valor_base'],
                'valor_pacote'      => $pacote['preco'],
                'desconto'          => $desconto,
                'observacoes'       => trim($dados['observacoes'] ?? ''),
            ]);

            return ['sucesso' => true, 'erros' => []];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return ['sucesso' => false, 'erros' => ['data_evento' => 'Este salão já está reservado para a data selecionada.']];
            }
            error_log('[ReservaService::atualizar] ' . $e->getMessage());
            return ['sucesso' => false, 'erros' => ['geral' => 'Não foi possível salvar as alterações. Tente novamente.']];
        }
    }

    public static function confirmar(int $id): bool
    {
        return Reserva::atualizarStatus($id, 'confirmada');
    }

    public static function cancelar(int $id): bool
    {
        return Reserva::atualizarStatus($id, 'cancelada');
    }

    private static function validarDadosBasicos(array $dados): array
    {
        return Validator::validate($dados, [
            'salao_id'          => 'required|integer',
            'pacote_id'         => 'required|integer',
            'data_evento'       => 'required|date|data_futura_ou_hoje',
            'turno'             => 'required|in:manha,tarde,noite',
            'tipo_evento'       => 'required|max:80',
            'numero_convidados' => 'required|integer|maior_que_zero',
        ]);
    }
}
