<?php

declare(strict_types=1);

class Pagamento
{
    public static function listarPorReserva(int $reservaId): array
    {
        $stmt = Database::conectar()->prepare(
            'SELECT * FROM pagamentos WHERE reserva_id = :reserva_id ORDER BY data_pagamento, id'
        );
        $stmt->execute([':reserva_id' => $reservaId]);
        return $stmt->fetchAll();
    }

    public static function totalPagoPorReserva(int $reservaId): float
    {
        $stmt = Database::conectar()->prepare(
            'SELECT COALESCE(SUM(valor), 0) AS total FROM pagamentos WHERE reserva_id = :reserva_id'
        );
        $stmt->execute([':reserva_id' => $reservaId]);
        return (float)$stmt->fetch()['total'];
    }

    public static function buscarPorId(int $id): ?array
    {
        $stmt = Database::conectar()->prepare('SELECT * FROM pagamentos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $pagamento = $stmt->fetch();
        return $pagamento ?: null;
    }

    public static function criar(array $dados): int
    {
        $stmt = Database::conectar()->prepare(
            'INSERT INTO pagamentos (reserva_id, valor, data_pagamento, forma_pagamento, tipo, observacoes)
             VALUES (:reserva_id, :valor, :data_pagamento, :forma_pagamento, :tipo, :observacoes)'
        );
        $stmt->execute([
            ':reserva_id'      => $dados['reserva_id'],
            ':valor'           => $dados['valor'],
            ':data_pagamento'  => $dados['data_pagamento'],
            ':forma_pagamento' => $dados['forma_pagamento'],
            ':tipo'            => $dados['tipo'],
            ':observacoes'     => $dados['observacoes'] !== '' ? ($dados['observacoes'] ?? null) : null,
        ]);
        return (int)Database::conectar()->lastInsertId();
    }

    public static function atualizar(int $id, array $dados): bool
    {
        $stmt = Database::conectar()->prepare(
            'UPDATE pagamentos SET valor = :valor, data_pagamento = :data_pagamento,
                forma_pagamento = :forma_pagamento, tipo = :tipo, observacoes = :observacoes
             WHERE id = :id'
        );
        return $stmt->execute([
            ':valor'           => $dados['valor'],
            ':data_pagamento'  => $dados['data_pagamento'],
            ':forma_pagamento' => $dados['forma_pagamento'],
            ':tipo'            => $dados['tipo'],
            ':observacoes'     => $dados['observacoes'] !== '' ? ($dados['observacoes'] ?? null) : null,
            ':id'              => $id,
        ]);
    }

    public static function excluir(int $id): bool
    {
        $stmt = Database::conectar()->prepare('DELETE FROM pagamentos WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /** Usado pela listagem administrativa geral de pagamentos (todas as reservas). */
    public static function listarTodosComReserva(): array
    {
        $stmt = Database::conectar()->query(
            "SELECT p.*, r.data_evento, s.nome AS salao_nome, u.nome AS cliente_nome
             FROM pagamentos p
             INNER JOIN reservas r ON r.id = p.reserva_id
             INNER JOIN saloes s   ON s.id = r.salao_id
             INNER JOIN clientes c ON c.id = r.cliente_id
             INNER JOIN usuarios u ON u.id = c.usuario_id
             ORDER BY p.data_pagamento DESC, p.id DESC"
        );
        return $stmt->fetchAll();
    }

    /** Usado pelo relatorio de faturamento por periodo. */
    public static function listarParaFaturamento(string $dataInicio, string $dataFim, ?int $salaoId, ?string $formaPagamento): array
    {
        $sql = "SELECT p.id AS pagamento_id, p.data_pagamento, p.valor, p.forma_pagamento, p.tipo,
                       r.id AS reserva_id, r.data_evento,
                       s.nome AS salao_nome, u.nome AS cliente_nome
                FROM pagamentos p
                INNER JOIN reservas r ON r.id = p.reserva_id
                INNER JOIN saloes s   ON s.id = r.salao_id
                INNER JOIN clientes c ON c.id = r.cliente_id
                INNER JOIN usuarios u ON u.id = c.usuario_id
                WHERE p.data_pagamento BETWEEN :inicio AND :fim";
        $params = [':inicio' => $dataInicio, ':fim' => $dataFim];

        if ($salaoId) {
            $sql .= ' AND r.salao_id = :salao_id';
            $params[':salao_id'] = $salaoId;
        }
        if ($formaPagamento) {
            $sql .= ' AND p.forma_pagamento = :forma_pagamento';
            $params[':forma_pagamento'] = $formaPagamento;
        }

        $sql .= ' ORDER BY p.data_pagamento ASC, p.id ASC';

        $stmt = Database::conectar()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Subtotal por salao, mesmos filtros do relatorio de faturamento. */
    public static function subtotalPorSalao(string $dataInicio, string $dataFim, ?int $salaoId, ?string $formaPagamento): array
    {
        $sql = "SELECT s.nome AS salao_nome, COUNT(*) AS qtd_pagamentos, SUM(p.valor) AS total_recebido
                FROM pagamentos p
                INNER JOIN reservas r ON r.id = p.reserva_id
                INNER JOIN saloes s   ON s.id = r.salao_id
                WHERE p.data_pagamento BETWEEN :inicio AND :fim";
        $params = [':inicio' => $dataInicio, ':fim' => $dataFim];

        if ($salaoId) {
            $sql .= ' AND r.salao_id = :salao_id';
            $params[':salao_id'] = $salaoId;
        }
        if ($formaPagamento) {
            $sql .= ' AND p.forma_pagamento = :forma_pagamento';
            $params[':forma_pagamento'] = $formaPagamento;
        }

        $sql .= ' GROUP BY s.id, s.nome ORDER BY total_recebido DESC';

        $stmt = Database::conectar()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
