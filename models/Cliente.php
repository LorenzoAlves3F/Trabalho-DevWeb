<?php

declare(strict_types=1);

class Cliente
{
    public static function buscarPorId(int $id): ?array
    {
        $stmt = Database::conectar()->prepare(
            'SELECT c.*, u.nome, u.email, u.ativo, u.tipo
             FROM clientes c
             INNER JOIN usuarios u ON u.id = c.usuario_id
             WHERE c.id = :id'
        );
        $stmt->execute([':id' => $id]);
        $cliente = $stmt->fetch();
        return $cliente ?: null;
    }

    public static function buscarPorUsuarioId(int $usuarioId): ?array
    {
        $stmt = Database::conectar()->prepare('SELECT * FROM clientes WHERE usuario_id = :usuario_id');
        $stmt->execute([':usuario_id' => $usuarioId]);
        $cliente = $stmt->fetch();
        return $cliente ?: null;
    }

    public static function cpfExiste(string $cpf, ?int $excetoId = null): bool
    {
        $sql = 'SELECT id FROM clientes WHERE cpf = :cpf';
        $params = [':cpf' => $cpf];
        if ($excetoId !== null) {
            $sql .= ' AND id != :excetoId';
            $params[':excetoId'] = $excetoId;
        }
        $stmt = Database::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public static function listarComUsuario(): array
    {
        $stmt = Database::conectar()->query(
            'SELECT c.id, c.telefone, c.cpf, c.endereco, u.id AS usuario_id, u.nome, u.email, u.ativo
             FROM clientes c
             INNER JOIN usuarios u ON u.id = c.usuario_id
             ORDER BY u.nome'
        );
        return $stmt->fetchAll();
    }

    public static function criar(int $usuarioId, string $telefone, string $cpf, string $endereco): int
    {
        $stmt = Database::conectar()->prepare(
            'INSERT INTO clientes (usuario_id, telefone, cpf, endereco) VALUES (:usuario_id, :telefone, :cpf, :endereco)'
        );
        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':telefone'   => $telefone,
            ':cpf'        => $cpf,
            ':endereco'   => $endereco,
        ]);
        return (int)Database::conectar()->lastInsertId();
    }

    /** Usado pelo cliente ao editar o proprio perfil (CPF nao e editavel pelo titular). */
    public static function atualizar(int $id, string $telefone, string $endereco): bool
    {
        $stmt = Database::conectar()->prepare(
            'UPDATE clientes SET telefone = :telefone, endereco = :endereco WHERE id = :id'
        );
        return $stmt->execute([':telefone' => $telefone, ':endereco' => $endereco, ':id' => $id]);
    }

    /** Usado pelo admin, que pode corrigir tambem o CPF cadastrado. */
    public static function atualizarCompleto(int $id, string $telefone, string $cpf, string $endereco): bool
    {
        $stmt = Database::conectar()->prepare(
            'UPDATE clientes SET telefone = :telefone, cpf = :cpf, endereco = :endereco WHERE id = :id'
        );
        return $stmt->execute([':telefone' => $telefone, ':cpf' => $cpf, ':endereco' => $endereco, ':id' => $id]);
    }
}
