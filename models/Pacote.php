<?php

declare(strict_types=1);

class Pacote
{
    public static function listarTodos(): array
    {
        $stmt = Database::conectar()->query('SELECT * FROM pacotes ORDER BY preco');
        return $stmt->fetchAll();
    }

    public static function listarAtivos(): array
    {
        $stmt = Database::conectar()->query('SELECT * FROM pacotes WHERE ativo = 1 ORDER BY preco');
        return $stmt->fetchAll();
    }

    public static function buscarPorId(int $id): ?array
    {
        $stmt = Database::conectar()->prepare('SELECT * FROM pacotes WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $pacote = $stmt->fetch();
        return $pacote ?: null;
    }

    public static function nomeExiste(string $nome, ?int $excetoId = null): bool
    {
        $sql = 'SELECT id FROM pacotes WHERE nome = :nome';
        $params = [':nome' => $nome];
        if ($excetoId !== null) {
            $sql .= ' AND id != :excetoId';
            $params[':excetoId'] = $excetoId;
        }
        $stmt = Database::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public static function criar(string $nome, ?string $descricao, float $preco): int
    {
        $stmt = Database::conectar()->prepare(
            'INSERT INTO pacotes (nome, descricao, preco) VALUES (:nome, :descricao, :preco)'
        );
        $stmt->execute([':nome' => $nome, ':descricao' => $descricao ?: null, ':preco' => $preco]);
        return (int)Database::conectar()->lastInsertId();
    }

    public static function atualizar(int $id, string $nome, ?string $descricao, float $preco): bool
    {
        $stmt = Database::conectar()->prepare(
            'UPDATE pacotes SET nome = :nome, descricao = :descricao, preco = :preco WHERE id = :id'
        );
        return $stmt->execute([':nome' => $nome, ':descricao' => $descricao ?: null, ':preco' => $preco, ':id' => $id]);
    }

    public static function definirAtivo(int $id, bool $ativo): bool
    {
        $stmt = Database::conectar()->prepare('UPDATE pacotes SET ativo = :ativo WHERE id = :id');
        return $stmt->execute([':ativo' => $ativo ? 1 : 0, ':id' => $id]);
    }
}
