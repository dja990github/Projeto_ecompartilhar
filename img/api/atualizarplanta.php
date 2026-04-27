<?php
require_once '../factory/bootstrap.php';
require_once '../factory/Planta.php';
require_once '../factory/UploadImagem.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar se usuário está logado
if (empty($_SESSION['usuario']['id']) || $_SESSION['usuario']['id'] === null) {
    http_response_code(401);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Você deve estar logado para atualizar plantas'
    ]);
    exit;
}

// Verificar CSRF token
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido'
    ]);
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Token de segurança inválido'
    ]);
    exit;
}

// Verificar ID da planta
if (empty($_POST['id'])) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID da planta não fornecido'
    ]);
    exit;
}

try {
    $planta = new Planta();
    $plantaAtual = $planta->obter((int) $_POST['id']);

    if (!$plantaAtual) {
        http_response_code(404);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Planta não encontrada'
        ]);
        exit;
    }

    $dados = [
        'nome' => $_POST['nome'] ?? '',
        'descricao' => $_POST['descricao'] ?? '',
        'tipo' => $_POST['tipo'] ?? '',
        'especie' => $_POST['especie'] ?? '',
        'tamanho' => $_POST['tamanho'] ?? '',
        'estado' => $_POST['estado'] ?? '',
        'contato' => $_POST['contato'] ?? '',
        'imagem' => $plantaAtual['imagem'] // Manter imagem atual por padrão
    ];

    // Processar novo upload de imagem
    if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
        $upload = new UploadImagem('../img/usr/');
        $resultadoUpload = $upload->processar($_FILES['foto']);

        if ($resultadoUpload['sucesso']) {
            // Deletar imagem antiga se existir
            if (!empty($plantaAtual['imagem']) && file_exists($plantaAtual['imagem'])) {
                unlink($plantaAtual['imagem']);
            }
            $dados['imagem'] = $resultadoUpload['caminho'];
        } else {
            http_response_code(400);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erro no upload: ' . $resultadoUpload['mensagem']
            ]);
            exit;
        }
    }

    $resultado = $planta->atualizar(
        (int) $_POST['id'],
        $_SESSION['usuario']['id'],
        $dados
    );

    http_response_code($resultado['sucesso'] ? 200 : 400);
    echo json_encode($resultado);

} catch (Exception $e) {
    error_log("Erro em atualizarplanta.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao processar solicitação'
    ]);
}
