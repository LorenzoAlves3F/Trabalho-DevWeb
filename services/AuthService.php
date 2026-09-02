<?php

declare(strict_types=1);

class AuthService
{
    /** @return array{usuario:array,cliente_id:?int}|null null quando e-mail/senha invalidos ou usuario inativo */
    public static function login(string $email, string $senha): ?array
    {
        $usuario = Usuario::buscarPorEmail($email);

        if (!$usuario || !$usuario['ativo'] || !password_verify($senha, $usuario['senha_hash'])) {
            return null;
        }

        $clienteId = null;
        if ($usuario['tipo'] === 'cliente') {
            $cliente = Cliente::buscarPorUsuarioId((int)$usuario['id']);
            $clienteId = $cliente ? (int)$cliente['id'] : null;
        }

        return ['usuario' => $usuario, 'cliente_id' => $clienteId];
    }

    /**
     * Cadastra usuario + cliente numa unica transacao (usado no auto-cadastro e no cadastro pelo admin).
     * @return array{sucesso:bool, erros:array<string,string>}
     */
    public static function registrarCliente(array $dados): array
    {
        $erros = Validator::validate($dados, [
            'nome'              => 'required|min:3|max:120',
            'email'             => 'required|email|max:150',
            'senha'             => 'required|senha_forte',
            'senha_confirmacao' => 'required|confirmado',
            'telefone'          => 'required|telefone',
            'cpf'               => 'required|cpf',
            'endereco'          => 'required|max:255',
        ]);

        if (isset($erros['senha_confirmacao']) && !isset($erros['senha'])) {
            $erros['senha'] = 'As senhas não conferem.';
        }

        if (!isset($erros['email']) && Usuario::emailExiste(trim($dados['email'] ?? ''))) {
            $erros['email'] = 'Este e-mail já está cadastrado.';
        }

        $cpfDigitos = Validator::apenasDigitos($dados['cpf'] ?? '');
        if (!isset($erros['cpf']) && Cliente::cpfExiste($cpfDigitos)) {
            $erros['cpf'] = 'Este CPF já está cadastrado.';
        }

        if (!empty($erros)) {
            return ['sucesso' => false, 'erros' => $erros];
        }

        $pdo = Database::conectar();

        try {
            $pdo->beginTransaction();

            $usuarioId = Usuario::criar(
                trim($dados['nome']),
                trim($dados['email']),
                password_hash($dados['senha'], PASSWORD_DEFAULT),
                'cliente'
            );

            Cliente::criar(
                $usuarioId,
                Validator::apenasDigitos($dados['telefone']),
                $cpfDigitos,
                trim($dados['endereco'])
            );

            $pdo->commit();

            return ['sucesso' => true, 'erros' => []];
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('[AuthService::registrarCliente] ' . $e->getMessage());

            if ($e->getCode() === '23000') {
                return ['sucesso' => false, 'erros' => ['email' => 'E-mail ou CPF já cadastrado. Verifique os dados e tente novamente.']];
            }

            return ['sucesso' => false, 'erros' => ['geral' => 'Não foi possível concluir o cadastro. Tente novamente.']];
        }
    }

    /** @return string|null o token em claro (para exibir na tela), ou null se o e-mail nao existir/estiver inativo */
    public static function solicitarResetSenha(string $email): ?string
    {
        $usuario = Usuario::buscarPorEmail($email);
        if (!$usuario || !$usuario['ativo']) {
            return null;
        }

        PasswordReset::invalidarTokensAnteriores((int)$usuario['id']);

        $tokenReal = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $tokenReal);
        $expiraEm = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        PasswordReset::criar((int)$usuario['id'], $tokenHash, $expiraEm);

        return $tokenReal;
    }

    public static function validarToken(string $tokenReal): ?array
    {
        $tokenHash = hash('sha256', $tokenReal);
        $registro = PasswordReset::buscarPorTokenHash($tokenHash);

        if (!$registro || (int)$registro['usado'] === 1) {
            return null;
        }

        // Comparado em PHP (nao via "expira_em > NOW()" no SQL) para nao depender do relogio/timezone
        // do servidor MySQL bater com o do PHP - expira_em foi gravado com date() do PHP.
        if (strtotime($registro['expira_em']) < time()) {
            return null;
        }

        return $registro;
    }

    public static function redefinirSenha(string $tokenReal, string $novaSenha): bool
    {
        $registro = self::validarToken($tokenReal);
        if (!$registro) {
            return false;
        }

        $pdo = Database::conectar();
        $pdo->beginTransaction();

        try {
            Usuario::atualizarSenha((int)$registro['usuario_id'], password_hash($novaSenha, PASSWORD_DEFAULT));
            PasswordReset::marcarComoUsado((int)$registro['id']);
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('[AuthService::redefinirSenha] ' . $e->getMessage());
            return false;
        }
    }
}
