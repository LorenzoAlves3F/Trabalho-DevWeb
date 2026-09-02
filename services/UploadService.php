<?php

declare(strict_types=1);

class UploadService
{
    private const TIPOS_PERMITIDOS = ['image/jpeg' => 'jpg', 'image/png' => 'png'];

    /**
     * Valida e move o arquivo enviado ($_FILES['foto']) para a pasta publica de uploads de saloes.
     * O tipo real do arquivo e verificado via finfo (nao confia na extensao/MIME informado pelo navegador).
     * @return array{sucesso:bool, nome_arquivo?:string, erro?:string}
     */
    public static function salvarFotoSalao(array $arquivo): array
    {
        if (empty($arquivo['name'])) {
            return ['sucesso' => true]; // upload opcional - nada para salvar
        }

        if ($arquivo['error'] !== UPLOAD_ERR_OK) {
            return ['sucesso' => false, 'erro' => 'Falha ao enviar o arquivo. Tente novamente.'];
        }

        if ($arquivo['size'] > UPLOAD_MAX_BYTES) {
            return ['sucesso' => false, 'erro' => 'Envie uma imagem JPG ou PNG de até 2MB.'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($arquivo['tmp_name']);

        if (!isset(self::TIPOS_PERMITIDOS[$mime])) {
            return ['sucesso' => false, 'erro' => 'Envie uma imagem JPG ou PNG de até 2MB.'];
        }

        if (!is_dir(UPLOAD_DIR_ABSOLUTO)) {
            mkdir(UPLOAD_DIR_ABSOLUTO, 0755, true);
        }

        $nomeArquivo = uniqid('salao_', true) . '.' . self::TIPOS_PERMITIDOS[$mime];
        $destino = UPLOAD_DIR_ABSOLUTO . $nomeArquivo;

        if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
            return ['sucesso' => false, 'erro' => 'Não foi possível salvar a imagem. Tente novamente.'];
        }

        return ['sucesso' => true, 'nome_arquivo' => UPLOAD_DIR_PUBLICO . $nomeArquivo];
    }
}
