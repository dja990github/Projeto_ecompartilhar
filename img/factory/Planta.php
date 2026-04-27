<?php
require_once 'conexao.php';

class Planta
{
    private $conn;
    private $tabela = 'tbplantas';

    public function __construct()
    {
        $this->conn = Caminho::getConn();
    }

    /**
     * Validar dados da planta
     */
    private function validar($dados)
    {
        $erros = [];

        // Nome obrigatório
        if (empty(trim($dados['nome'] ?? ''))) {
            $erros[] = 'Nome da planta é obrigatório';
        } elseif (strlen(trim($dados['nome'])) > 100) {
            $erros[] = 'Nome não pode ter mais de 100 caracteres';
        }

        // Descrição obrigatória
        if (empty(trim($dados['descricao'] ?? ''))) {
            $erros[] = 'Descrição é obrigatória';
        } elseif (strlen(trim($dados['descricao'])) > 1000) {
            $erros[] = 'Descrição não pode ter mais de 1000 caracteres';
        }

        // Tipo (troca ou doação)
        if (empty($dados['tipo'] ?? '')) {
            $erros[] = 'Tipo (Troca/Doação) é obrigatório';
        }

        // Espécie (opcional, mas validar tamanho)
        if (!empty($dados['especie']) && strlen(trim($dados['especie'])) > 100) {
            $erros[] = 'Espécie não pode ter mais de 100 caracteres';
        }

        // Tamanho (opcional)
        if (!empty($dados['tamanho']) && strlen(trim($dados['tamanho'])) > 50) {
            $erros[] = 'Tamanho não pode ter mais de 50 caracteres';
        }

        // Estado (opcional)
        if (!empty($dados['estado']) && strlen(trim($dados['estado'])) > 50) {
            $erros[] = 'Estado não pode ter mais de 50 caracteres';
        }

        // Contato (opcional)
        if (!empty($dados['contato']) && strlen(trim($dados['contato'])) > 100) {
            $erros[] = 'Contato não pode ter mais de 100 caracteres';
        }

        return $erros;
    }

    /**
     * Sanitizar dados
     */
    private function sanitizar($dados)
    {
        $sanitizado = [];

        $campos = ['nome', 'descricao', 'especie', 'tamanho', 'estado', 'contato', 'imagem'];

        foreach ($campos as $campo) {
            if (isset($dados[$campo])) {
                $sanitizado[$campo] = trim(strip_tags($dados[$campo]));
            }
        }

        return $sanitizado;
    }

    /**
     * Criar nova planta
     */
    public function criar($usuario_id, $dados)
    {
        try {
            // Validar dados
            $erros = $this->validar($dados);
            if (!empty($erros)) {
                return [
                    'sucesso' => false,
                    'mensagem' => implode(', ', $erros)
                ];
            }

            // Verificar se usuário está logado
            if (empty($usuario_id)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Você deve estar logado para cadastrar plantas'
                ];
            }

            $sanitizado = $this->sanitizar($dados);

            // Determinar tipo de troca/doação
            $troca = ($dados['tipo'] === 'troca') ? true : false;
            $doacao = ($dados['tipo'] === 'troca') ? false : true;

            $sql = "INSERT INTO {$this->tabela} 
                    (usuario_id, nome, descricao, troca, doacao, especie, tamanho, estado, imagem, contato) 
                    VALUES 
                    (:usuario_id, :nome, :descricao, :troca, :doacao, :especie, :tamanho, :estado, :imagem, :contato)";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':usuario_id' => (int) $usuario_id,
                ':nome' => $sanitizado['nome'],
                ':descricao' => $sanitizado['descricao'],
                ':troca' => (int) $troca,
                ':doacao' => (int) $doacao,
                ':especie' => $sanitizado['especie'] ?? '',
                ':tamanho' => $sanitizado['tamanho'] ?? '',
                ':estado' => $sanitizado['estado'] ?? '',
                ':imagem' => $sanitizado['imagem'] ?? '',
                ':contato' => $sanitizado['contato'] ?? ''
            ]);

            return [
                'sucesso' => true,
                'mensagem' => 'Planta cadastrada com sucesso!',
                'id' => $this->conn->lastInsertId()
            ];

        } catch (PDOException $e) {
            error_log("Erro ao criar planta: " . $e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao cadastrar planta. Tente novamente.'
            ];
        }
    }

    /**
     * Obter planta por ID
     */
    public function obter($id)
    {
        try {
            $sql = "SELECT * FROM {$this->tabela} WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => (int) $id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao obter planta: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Listar plantas do usuário
     */
    public function listarDoUsuario($usuario_id, $limite = 50, $offset = 0)
    {
        try {
            $sql = "SELECT * FROM {$this->tabela} 
                    WHERE usuario_id = :usuario_id 
                    ORDER BY data_cad DESC 
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':usuario_id' => (int) $usuario_id,
                ':limit' => (int) $limite,
                ':offset' => (int) $offset
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao listar plantas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualizar planta
     */
    public function atualizar($id, $usuario_id, $dados)
    {
        try {
            // Verificar se planta pertence ao usuário
            $planta = $this->obter($id);

            if (!$planta) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Planta não encontrada'
                ];
            }

            if ($planta['usuario_id'] != $usuario_id) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Você não tem permissão para editar esta planta'
                ];
            }

            // Validar dados
            $erros = $this->validar($dados);
            if (!empty($erros)) {
                return [
                    'sucesso' => false,
                    'mensagem' => implode(', ', $erros)
                ];
            }

            $sanitizado = $this->sanitizar($dados);

            // Determinar tipo
            $troca = ($dados['tipo'] === 'troca') ? true : false;
            $doacao = ($dados['tipo'] === 'troca') ? false : true;

            $sql = "UPDATE {$this->tabela} 
                    SET nome = :nome, 
                        descricao = :descricao, 
                        troca = :troca, 
                        doacao = :doacao, 
                        especie = :especie, 
                        tamanho = :tamanho, 
                        estado = :estado, 
                        contato = :contato
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':nome' => $sanitizado['nome'],
                ':descricao' => $sanitizado['descricao'],
                ':troca' => (int) $troca,
                ':doacao' => (int) $doacao,
                ':especie' => $sanitizado['especie'] ?? '',
                ':tamanho' => $sanitizado['tamanho'] ?? '',
                ':estado' => $sanitizado['estado'] ?? '',
                ':contato' => $sanitizado['contato'] ?? '',
                ':id' => (int) $id
            ]);

            return [
                'sucesso' => true,
                'mensagem' => 'Planta atualizada com sucesso!'
            ];

        } catch (PDOException $e) {
            error_log("Erro ao atualizar planta: " . $e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao atualizar planta. Tente novamente.'
            ];
        }
    }

    /**
     * Deletar planta
     */
    public function deletar($id, $usuario_id)
    {
        try {
            // Verificar se planta pertence ao usuário
            $planta = $this->obter($id);

            if (!$planta) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Planta não encontrada'
                ];
            }

            if ($planta['usuario_id'] != $usuario_id) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Você não tem permissão para deletar esta planta'
                ];
            }

            $sql = "DELETE FROM {$this->tabela} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => (int) $id]);

            return [
                'sucesso' => true,
                'mensagem' => 'Planta deletada com sucesso!'
            ];

        } catch (PDOException $e) {
            error_log("Erro ao deletar planta: " . $e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao deletar planta. Tente novamente.'
            ];
        }
    }

    /**
     * Listar todas as plantas (para busca/públicas)
     */
    public function listarTodas($limite = 50, $offset = 0)
    {
        try {
            $sql = "SELECT * FROM {$this->tabela} 
                    ORDER BY data_cad DESC 
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':limit' => (int) $limite,
                ':offset' => (int) $offset
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao listar todas as plantas: " . $e->getMessage());
            return [];
        }
    }
}
