<?php

declare(strict_types=1);

class RelatorioService
{
    /** @return array{linhas:array,subtotais:array,total:float,inicio:string,fim:string} */
    public static function faturamentoPorPeriodo(string $dataInicio, string $dataFim, ?int $salaoId, ?string $formaPagamento): array
    {
        $linhas = Pagamento::listarParaFaturamento($dataInicio, $dataFim, $salaoId, $formaPagamento);
        $subtotais = Pagamento::subtotalPorSalao($dataInicio, $dataFim, $salaoId, $formaPagamento);

        $total = 0.0;
        foreach ($linhas as $linha) {
            $total += (float)$linha['valor'];
        }

        return [
            'linhas'    => $linhas,
            'subtotais' => $subtotais,
            'total'     => $total,
            'inicio'    => $dataInicio,
            'fim'       => $dataFim,
        ];
    }

    /** @return array{linhas:array,inicio:string,fim:string} */
    public static function ocupacaoSaloes(string $dataInicio, string $dataFim, ?int $salaoId, ?string $status): array
    {
        $linhas = Reserva::listarParaOcupacao($dataInicio, $dataFim, $salaoId, $status);

        return [
            'linhas' => $linhas,
            'inicio' => $dataInicio,
            'fim'    => $dataFim,
        ];
    }

    /** Intervalo padrao (mes corrente) usado antes do usuario aplicar qualquer filtro. */
    public static function periodoPadrao(): array
    {
        return [
            'inicio' => date('Y-m-01'),
            'fim'    => date('Y-m-t'),
        ];
    }
}
