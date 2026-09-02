<?php

declare(strict_types=1);

class Salao
{
    public static function listarTodos(): array
    {
        $stmt = Database::conectar()->query('SELECT * FROM saloes ORDER BY nome');
        return $stmt->fetchAll();
    }

    public static function listarAtivos(): array
    {
        $stmt = Database::conectar()->query('SELECT * FROM saloes WHERE ativo = 1 ORDER BY nome');
        return $stmt->fetchAll();
    }

    public static function buscarPorId(int $id): ?array
    {
        $stmt = Database::conectar()->prepare('SELECT * FROM saloes WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $salao = $stmt->fetch();
        return $salao ?: null;
    }

    public static function nomeExiste(string $nome, ?int $excetoId = null): bool
    {
        $sql = 'SELECT id FROM saloes WHERE nome = :nome';
        $params = [':nome' => $nome];
        if ($excetoId !== null) {
            $sql .= ' AND id != :excetoId';
            $params[':excetoId'] = $excetoId;
        }
        $stmt = Database::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public static function criar(array $dados): int
    {
        $stmt = Database::conectar()->prepare(
            'INSERT INTO saloes (nome, capacidade, descricao, valor_base, foto)
             VALUES (:nome, :capacidade, :descricao, :valor_base, :foto)'
        );
        $stmt->execute([
            ':nome'       => $dados['nome'],
            ':capacidade' => $dados['capacidade'],
            ':descricao'  => $dados['descricao'] !== '' ? $dados['descricao'] : null,
            ':valor_base' => $dados['valor_base'],
            ':foto'       => $dados['foto'] ?? null,
        ]);
        return (int)Database::conectar()->lastInsertId();
    }

    public static function atualizar(int $id, array $dados): bool
    {
        $sql = 'UPDATE saloes SET nome = :nome, capacidade = :capacidade, descricao = :descricao, valor_base = :valor_base';
        $params = [
            ':nome'       => $dados['nome'],
            ':capacidade' => $dados['capacidade'],
            ':descricao'  => $dados['descricao'] !== '' ? $dados['descricao'] : null,
            ':valor_base' => $dados['valor_base'],
            ':id'         => $id,
        ];
        if (!empty($dados['foto'])) {
            $sql .= ', foto = :foto';
            $params[':foto'] = $dados['foto'];
        }
        $sql .= ' WHERE id = :id';

        $stmt = Database::conectar()->prepare($sql);
        return $stmt->execute($params);
    }

    public static function definirAtivo(int $id, bool $ativo): bool
    {
        $stmt = Database::conectar()->prepare('UPDATE saloes SET ativo = :ativo WHERE id = :id');
        return $stmt->execute([':ativo' => $ativo ? 1 : 0, ':id' => $id]);
    }
}
