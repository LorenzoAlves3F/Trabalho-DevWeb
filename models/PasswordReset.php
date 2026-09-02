<?php

declare(strict_types=1);

class PasswordReset
{
    public static function invalidarTokensAnteriores(int $usuarioId): void
    {
        $stmt = Database::conectar()->prepare(
            'UPDATE password_resets SET usado = 1 WHERE usuario_id = :usuario_id AND usado = 0'
        );
        $stmt->execute([':usuario_id' => $usuarioId]);
    }

    public static function criar(int $usuarioId, string $tokenHash, string $expiraEm): void
    {
        $stmt = Database::conectar()->prepare(
            'INSERT INTO password_resets (usuario_id, token, expira_em) VALUES (:usuario_id, :token, :expira_em)'
        );
        $stmt->execute([':usuario_id' => $usuarioId, ':token' => $tokenHash, ':expira_em' => $expiraEm]);
    }

    public static function buscarValidoPorTokenHash(string $tokenHash): ?array
    {
        $stmt = Database::conectar()->prepare(
            'SELECT * FROM password_resets WHERE token = :token AND usado = 0 AND expira_em > NOW()'
        );
        $stmt->execute([':token' => $tokenHash]);
        $registro = $stmt->fetch();
        return $registro ?: null;
    }

    public static function marcarComoUsado(int $id): void
    {
        $stmt = Database::conectar()->prepare('UPDATE password_resets SET usado = 1 WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
