<?php
require_once '../factory/bootstrap.php';

$conn = Caminho::getConn();
$user = $_SESSION['usuario'];

$campo = $_POST['campo'] ?? null;
$valor = trim($_POST['valor'] ?? '');

$permitidos = ['nome', 'email', 'telefone'];

if (!in_array($campo, $permitidos)) {
    echo json_encode(["sucesso" => false]);
    exit;
}

/* VALIDAÇÃO */
if ($campo === 'email' && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["sucesso" => false, "erro" => "Email inválido"]);
    exit;
}

/* ================= USUÁRIO ANÔNIMO ================= */
if (empty($user['id'])) {

    $token = bin2hex(random_bytes(32));

    $stmt = $conn->prepare("
        INSERT INTO tbusuarios (nome, email, telefone, token_login)
        VALUES (:nome, :email, :telefone, :token)
    ");

    $stmt->execute([
        ':nome' => $user['nome'],
        ':email' => $campo === 'email' ? $valor : '',
        ':telefone' => '',
        ':token' => $token
    ]);

    $id = $conn->lastInsertId();

    $_SESSION['usuario'] = [
        'id' => $id,
        'nome' => $user['nome'],
        'email' => $campo === 'email' ? $valor : '',
        'telefone' => '',
        'imagem' => 'perfil.png',
        'token_login' => $token,
        'anonimo' => false
    ];

    setcookie("login_token", $token, time() + (86400 * 30), "/");
}


$stmt = $conn->prepare("UPDATE tbusuarios SET $campo = :v WHERE id = :id");

$ok = $stmt->execute([
    ':v' => $valor,
    ':id' => $_SESSION['usuario']['id']
]);

if ($ok) {
    $_SESSION['usuario'][$campo] = $valor;
}

echo json_encode(["sucesso" => $ok]);