<?php

class UploadImagem
{
    private $pastaDestino = '../../img/usr/';
    private $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private $tamanhoMaximo = 5242880; // 5MB

    public function __construct($pastaDestino = null)
    {
        if ($pastaDestino) {
            $this->pastaDestino = $pastaDestino;
        }
    }

    /**
     * Processar upload de arquivo
     */
    public function processar($arquivo)
    {
        // Verificar se arquivo foi enviado
        if (!isset($arquivo['tmp_name']) || empty($arquivo['tmp_name'])) {
            return [
                'sucesso' => false,
                'caminho' => null,
                'mensagem' => 'Nenhum arquivo enviado'
            ];
        }

        // Validar arquivo
        $validacao = $this->validar($arquivo);
        if (!$validacao['sucesso']) {
            return $validacao;
        }

        // Gerar nome único
        $nomeOriginal = basename($arquivo['name']);
        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
        $nomeUnico = hash('sha256', time() . $nomeOriginal . mt_rand()) . '.' . $extensao;
        $caminhoDestino = $this->pastaDestino . $nomeUnico;

        // Mover arquivo
        if (move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
            return [
                'sucesso' => true,
                'caminho' => $caminhoDestino,
                'nome' => $nomeUnico,
                'mensagem' => 'Imagem enviada com sucesso!'
            ];
        } else {
            return [
                'sucesso' => false,
                'caminho' => null,
                'mensagem' => 'Erro ao mover arquivo'
            ];
        }
    }

    /**
     * Validar arquivo
     */
    private function validar($arquivo)
    {
        // Verificar tamanho
        if ($arquivo['size'] > $this->tamanhoMaximo) {
            return [
                'sucesso' => false,
                'mensagem' => 'Arquivo muito grande. Máximo: 5MB'
            ];
        }

        // Verificar extensão
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        if (!in_array($extensao, $this->extensoesPermitidas)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Tipo de arquivo não permitido. Use: JPG, PNG, GIF, WEBP'
            ];
        }

        // Verificar tipo MIME
        $tipoMime = mime_content_type($arquivo['tmp_name']);
        $tipesPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($tipoMime, $tipesPermitidos)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Tipo de arquivo inválido'
            ];
        }

        return ['sucesso' => true];
    }

    /**
     * Deletar arquivo
     */
    public function deletar($caminho)
    {
        if (file_exists($caminho)) {
            return unlink($caminho);
        }
        return false;
    }
}
