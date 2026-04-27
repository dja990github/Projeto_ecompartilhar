<?php
require_once '../factory/bootstrap.php';
require_once '../factory/Planta.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar se usuário está logado
if (empty($_SESSION['usuario']['id']) || $_SESSION['usuario']['id'] === null) {
    http_response_code(401);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Você deve estar logado para deletar plantas'
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

    $resultado = $planta->deletar(
        (int) $_POST['id'],
        $_SESSION['usuario']['id']
    );

    http_response_code($resultado['sucesso'] ? 200 : 400);
    echo json_encode($resultado);

} catch (Exception $e) {
    error_log("Erro em deletarplanta.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao processar solicitação'
    ]);
}
