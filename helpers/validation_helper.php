<?php

declare(strict_types=1);

/**
 * Validador generico server-side, usado por todos os formularios do sistema.
 * Uso: Validator::validate($_POST, ['email' => 'required|email', 'nome' => 'required|min:3|max:100']);
 */
class Validator
{
    /**
     * @param array<string,mixed>  $dados
     * @param array<string,string> $regras
     * @return array<string,string> erros [campo => mensagem]; vazio quando tudo passou
     */
    public static function validate(array $dados, array $regras): array
    {
        $erros = [];

        foreach ($regras as $campo => $listaRegras) {
            $valor = $dados[$campo] ?? '';
            $valor = is_string($valor) ? trim($valor) : $valor;

            foreach (explode('|', $listaRegras) as $regra) {
                $parametro = null;
                if (str_contains($regra, ':')) {
                    [$regra, $parametro] = explode(':', $regra, 2);
                }

                $mensagem = self::checar($regra, $parametro, $campo, $valor, $dados);
                if ($mensagem !== null) {
                    $erros[$campo] = $mensagem;
                    break; // uma mensagem por campo é suficiente
                }
            }
        }

        return $erros;
    }

    private static function checar(string $regra, ?string $parametro, string $campo, mixed $valor, array $dados): ?string
    {
        // "required" é a única regra que também dispara para string vazia; as demais
        // toleram campo vazio (deixa passar) para não duplicar mensagem quando combinada com required.
        if ($regra !== 'required' && ($valor === '' || $valor === null)) {
            return null;
        }

        return match ($regra) {
            'required'    => ($valor === '' || $valor === null) ? 'Este campo é obrigatório.' : null,
            'email'       => filter_var($valor, FILTER_VALIDATE_EMAIL) ? null : 'Informe um e-mail válido.',
            'min'         => mb_strlen((string)$valor) >= (int)$parametro ? null : "Deve ter no mínimo {$parametro} caracteres.",
            'max'         => mb_strlen((string)$valor) <= (int)$parametro ? null : "Deve ter no máximo {$parametro} caracteres.",
            'numeric'     => is_numeric($valor) ? null : 'Deve ser um número.',
            'integer'     => filter_var($valor, FILTER_VALIDATE_INT) !== false ? null : 'Deve ser um número inteiro.',
            'maior_que_zero' => (float)$valor > 0 ? null : 'Deve ser maior que zero.',
            'date'        => self::dataValida((string)$valor) ? null : 'Informe uma data válida.',
            'data_futura_ou_hoje' => ((string)$valor >= date('Y-m-d')) ? null : 'A data não pode ser no passado.',
            'data_nao_futura'     => ((string)$valor <= date('Y-m-d')) ? null : 'A data não pode ser no futuro.',
            'in'          => in_array($valor, explode(',', (string)$parametro), true) ? null : 'Selecione uma opção válida.',
            // Regra aplicada ao proprio campo "*_confirmacao" (ex: senha_confirmacao) -
            // compara com o campo base, removendo o sufixo (ex: senha).
            'confirmado'  => ($valor === ($dados[preg_replace('/_confirmacao$/', '', $campo)] ?? null)) ? null : 'Os valores não conferem.',
            'senha_forte' => preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', (string)$valor) ? null
                             : 'A senha deve ter no mínimo 8 caracteres, com letras maiúsculas, minúsculas e números.',
            'cpf'         => self::validarCPF((string)$valor) ? null : 'CPF inválido.',
            'telefone'    => self::telefoneValido((string)$valor) ? null : 'Telefone inválido. Use o formato (DD) 99999-9999.',
            default       => null,
        };
    }

    private static function dataValida(string $data): bool
    {
        $partes = explode('-', $data);
        if (count($partes) !== 3) {
            return false;
        }
        [$ano, $mes, $dia] = $partes;
        return checkdate((int)$mes, (int)$dia, (int)$ano);
    }

    private static function telefoneValido(string $telefone): bool
    {
        $digitos = self::apenasDigitos($telefone);
        return strlen($digitos) >= 10 && strlen($digitos) <= 11;
    }

    /** Algoritmo padrão de dígito verificador do CPF (rejeita sequências repetidas como 111.111.111-11). */
    public static function validarCPF(string $cpf): bool
    {
        $cpf = self::apenasDigitos($cpf);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($c = 0; $c < $t; $c++) {
                $soma += (int)$cpf[$c] * (($t + 1) - $c);
            }
            $digito = ((10 * $soma) % 11) % 10;
            if ((int)$cpf[$t] !== $digito) {
                return false;
            }
        }

        return true;
    }

    public static function apenasDigitos(string $valor): string
    {
        return preg_replace('/\D/', '', $valor) ?? '';
    }
}
