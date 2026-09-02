<?php

declare(strict_types=1);

class PacoteItem
{
    public static function listarPorPacote(int $pacoteId): array
    {
        $stmt = Database::conectar()->prepare(
            'SELECT * FROM pacote_itens WHERE pacote_id = :pacote_id ORDER BY ordem, id'
        );
        $stmt->execute([':pacote_id' => $pacoteId]);
        return $stmt->fetchAll();
    }

    /** Apaga os itens atuais do pacote e grava a nova lista (ordem = posicao no array). */
    public static function substituirItens(int $pacoteId, array $descricoes): void
    {
        $pdo = Database::conectar();

        $delete = $pdo->prepare('DELETE FROM pacote_itens WHERE pacote_id = :pacote_id');
        $delete->execute([':pacote_id' => $pacoteId]);

        $insert = $pdo->prepare(
            'INSERT INTO pacote_itens (pacote_id, descricao_item, ordem) VALUES (:pacote_id, :descricao_item, :ordem)'
        );

        $ordem = 0;
        foreach ($descricoes as $descricao) {
            $descricao = trim((string)$descricao);
            if ($descricao === '') {
                continue;
            }
            $insert->execute([
                ':pacote_id'      => $pacoteId,
                ':descricao_item' => $descricao,
                ':ordem'          => $ordem++,
            ]);
        }
    }
}
