<?php

declare(strict_types=1);

class Reserva
{
    private const SELECT_BASE = '
        SELECT r.*, s.nome AS salao_nome, s.capacidade AS salao_capacidade,
               p.nome AS pacote_nome, c.id AS cliente_id, u.nome AS cliente_nome, u.email AS cliente_email
        FROM reservas r
        INNER JOIN saloes s   ON s.id = r.salao_id
        INNER JOIN pacotes p  ON p.id = r.pacote_id
        INNER JOIN clientes c ON c.id = r.cliente_id
        INNER JOIN usuarios u ON u.id = c.usuario_id
    ';

    public static function buscarPorId(int $id): ?array
    {
        $stmt = Database::conectar()->prepare(self::SELECT_BASE . ' WHERE r.id = :id');
        $stmt->execute([':id' => $id]);
        $reserva = $stmt->fetch();
        return $reserva ?: null;
    }

    /** @param array{status?:string,salao_id?:int,cliente_id?:int,data_inicio?:string,data_fim?:string} $filtros */
    public static function listar(array $filtros = []): array
    {
        $sql = self::SELECT_BASE . ' WHERE 1=1';
        $params = [];

        if (!empty($filtros['status'])) {
            $sql .= ' AND r.status = :status';
            $params[':status'] = $filtros['status'];
        }
        if (!empty($filtros['salao_id'])) {
            $sql .= ' AND r.salao_id = :salao_id';
            $params[':salao_id'] = $filtros['salao_id'];
        }
        if (!empty($filtros['cliente_id'])) {
            $sql .= ' AND r.cliente_id = :cliente_id';
            $params[':cliente_id'] = $filtros['cliente_id'];
        }
        if (!empty($filtros['data_inicio'])) {
            $sql .= ' AND r.data_evento >= :data_inicio';
            $params[':data_inicio'] = $filtros['data_inicio'];
        }
        if (!empty($filtros['data_fim'])) {
            $sql .= ' AND r.data_evento <= :data_fim';
            $params[':data_fim'] = $filtros['data_fim'];
        }

        $sql .= ' ORDER BY r.data_evento DESC, r.id DESC';

        $stmt = Database::conectar()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function listarPorCliente(int $clienteId): array
    {
        return self::listar(['cliente_id' => $clienteId]);
    }

    /** Existe reserva ATIVA (nao cancelada) no mesmo salao+data? Checagem preventiva antes do INSERT/UPDATE. */
    public static function existeConflito(int $salaoId, string $dataEvento, ?int $excetoReservaId = null): bool
    {
        $sql = "SELECT id FROM reservas WHERE salao_id = :salao_id AND data_evento = :data_evento AND status != 'cancelada'";
        $params = [':salao_id' => $salaoId, ':data_evento' => $dataEvento];
        if ($excetoReservaId !== null) {
            $sql .= ' AND id != :excetoId';
            $params[':excetoId'] = $excetoReservaId;
        }
        $stmt = Database::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public static function criar(array $dados): int
    {
        $stmt = Database::conectar()->prepare(
            'INSERT INTO reservas
                (cliente_id, salao_id, pacote_id, data_evento, turno, tipo_evento, numero_convidados,
                 status, valor_salao, valor_pacote, desconto, observacoes)
             VALUES
                (:cliente_id, :salao_id, :pacote_id, :data_evento, :turno, :tipo_evento, :numero_convidados,
                 :status, :valor_salao, :valor_pacote, :desconto, :observacoes)'
        );
        $stmt->execute([
            ':cliente_id'        => $dados['cliente_id'],
            ':salao_id'          => $dados['salao_id'],
            ':pacote_id'         => $dados['pacote_id'],
            ':data_evento'       => $dados['data_evento'],
            ':turno'             => $dados['turno'],
            ':tipo_evento'       => $dados['tipo_evento'],
            ':numero_convidados' => $dados['numero_convidados'],
            ':status'            => $dados['status'] ?? 'solicitada',
            ':valor_salao'       => $dados['valor_salao'],
            ':valor_pacote'      => $dados['valor_pacote'],
            ':desconto'          => $dados['desconto'] ?? 0,
            ':observacoes'       => $dados['observacoes'] !== '' ? ($dados['observacoes'] ?? null) : null,
        ]);
        return (int)Database::conectar()->lastInsertId();
    }

    public static function atualizar(int $id, array $dados): bool
    {
        $stmt = Database::conectar()->prepare(
            'UPDATE reservas SET
                salao_id = :salao_id, pacote_id = :pacote_id, data_evento = :data_evento, turno = :turno,
                tipo_evento = :tipo_evento, numero_convidados = :numero_convidados,
                valor_salao = :valor_salao, valor_pacote = :valor_pacote, desconto = :desconto,
                observacoes = :observacoes
             WHERE id = :id'
        );
        return $stmt->execute([
            ':salao_id'          => $dados['salao_id'],
            ':pacote_id'         => $dados['pacote_id'],
            ':data_evento'       => $dados['data_evento'],
            ':turno'             => $dados['turno'],
            ':tipo_evento'       => $dados['tipo_evento'],
            ':numero_convidados' => $dados['numero_convidados'],
            ':valor_salao'       => $dados['valor_salao'],
            ':valor_pacote'      => $dados['valor_pacote'],
            ':desconto'          => $dados['desconto'] ?? 0,
            ':observacoes'       => $dados['observacoes'] !== '' ? ($dados['observacoes'] ?? null) : null,
            ':id'                => $id,
        ]);
    }

    public static function atualizarStatus(int $id, string $status): bool
    {
        $stmt = Database::conectar()->prepare('UPDATE reservas SET status = :status WHERE id = :id');
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /** Usado pelo relatorio de agenda de ocupacao dos saloes. */
    public static function listarParaOcupacao(string $dataInicio, string $dataFim, ?int $salaoId, ?string $status): array
    {
        $sql = self::SELECT_BASE . ' WHERE r.data_evento BETWEEN :inicio AND :fim';
        $params = [':inicio' => $dataInicio, ':fim' => $dataFim];

        if ($status) {
            $sql .= ' AND r.status = :status';
            $params[':status'] = $status;
        } else {
            $sql .= " AND r.status != 'cancelada'";
        }

        if ($salaoId) {
            $sql .= ' AND r.salao_id = :salao_id';
            $params[':salao_id'] = $salaoId;
        }

        $sql .= " ORDER BY r.data_evento ASC, s.nome ASC, FIELD(r.turno,'manha','tarde','noite')";

        $stmt = Database::conectar()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
