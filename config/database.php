<?php

declare(strict_types=1);

/**
 * Conexao PDO unica (singleton) com o MySQL.
 * Prepared statements nativos (EMULATE_PREPARES=false) e excecoes em qualquer erro de banco.
 */
class Database
{
    private static ?PDO $conexao = null;

    private const HOST   = 'localhost';
    private const DBNAME = 'carlores';
    private const USUARIO = 'root';
    private const SENHA   = '';
    private const CHARSET = 'utf8mb4';

    public static function conectar(): PDO
    {
        if (self::$conexao === null) {
            $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DBNAME . ';charset=' . self::CHARSET;

            try {
                self::$conexao = new PDO($dsn, self::USUARIO, self::SENHA, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                error_log('[Database] Falha na conexao: ' . $e->getMessage());
                http_response_code(500);
                die('Nao foi possivel conectar ao banco de dados. Verifique se o MySQL do XAMPP esta em execucao e se o banco "carlores" foi importado (veja o README.md).');
            }
        }

        return self::$conexao;
    }
}
