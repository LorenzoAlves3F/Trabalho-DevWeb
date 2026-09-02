<?php

declare(strict_types=1);

class Usuario
{
    public static function buscarPorId(int $id): ?array
    {
        $stmt = Database::conectar()->prepare('SELECT * FROM usuarios WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public static function buscarPorEmail(string $email): ?array
    {
        $stmt = Database::conectar()->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public static function emailExiste(string $email, ?int $excetoId = null): bool
    {
        $sql = 'SELECT id FROM usuarios WHERE email = :email';
        $params = [':email' => $email];
        if ($excetoId !== null) {
            $sql .= ' AND id != :excetoId';
            $params[':excetoId'] = $excetoId;
        }
        $stmt = Database::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public static function criar(string $nome, string $email, string $senhaHash, string $tipo = 'cliente'): int
    {
        $stmt = Database::conectar()->prepare(
            'INSERT INTO usuarios (nome, email, senha_hash, tipo) VALUES (:nome, :email, :senha_hash, :tipo)'
        );
        $stmt->execute([
            ':nome'       => $nome,
            ':email'      => $email,
            ':senha_hash' => $senhaHash,
            ':tipo'       => $tipo,
        ]);
        return (int)Database::conectar()->lastInsertId();
    }

    public static function atualizarPerfil(int $id, string $nome, string $email): bool
    {
        $stmt = Database::conectar()->prepare('UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id');
        return $stmt->execute([':nome' => $nome, ':email' => $email, ':id' => $id]);
    }

    public static function atualizarSenha(int $id, string $senhaHash): bool
    {
        $stmt = Database::conectar()->prepare('UPDATE usuarios SET senha_hash = :senha_hash WHERE id = :id');
        return $stmt->execute([':senha_hash' => $senhaHash, ':id' => $id]);
    }

    public static function definirAtivo(int $id, bool $ativo): bool
    {
        $stmt = Database::conectar()->prepare('UPDATE usuarios SET ativo = :ativo WHERE id = :id');
        return $stmt->execute([':ativo' => $ativo ? 1 : 0, ':id' => $id]);
    }
}
