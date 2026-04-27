<?php
require_once 'conexao.php';

$conn = Caminho::getConn();

/* Se NÃO existe sessão */
if (!isset($_SESSION['usuario'])) {

    if (!empty($_COOKIE['login_token'])) {

        $stmt = $conn->prepare("SELECT * FROM tbusuarios WHERE token_login = :t LIMIT 1");
        $stmt->execute([':t' => $_COOKIE['login_token']]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['usuario'] = $user;
        } else {
            limparCookie();
            $_SESSION['usuario'] = criarAnonimo();
        }

    } else {
        $_SESSION['usuario'] = criarAnonimo();
    }
}

/* ================= FUNÇÕES ================= */

function criarAnonimo()
{
    return [
        'id' => null,
        'nome' => gerarNome(),
        'email' => '',
        'telefone' => '',
        'imagem' => 'perfil.png',
        'token_login' => null,
        'anonimo' => true
    ];
}

function gerarNome()
{
    $a = [
        'Feliz',
        'Ágil',
        'Sábio',
        'Verde',
        'Brilhante',
        'Silvestre',
        'Solar',
        'Nobre',
        'Calmo',
        'Vivo',
        'Lírio',
        'Dourado',
        'Sereno',
        'Livre',
        'Encantado',
        'Forte',
        'Gentil',
        'Radiante'
    ];

    $b = [
        'Plantador',
        'Jardineiro',
        'Botânico',
        'Cultivador',
        'Floricultor',
        'Semeador',
        'Agricultor',
        'Guardião',
        'Colhedor',
        'Explorador',
        'Curador',
        'Naturalista',
        'Cultor',
        'Verdejante',
        'EcoGuardião'
    ];

    return $a[array_rand($a)] . ' ' . $b[array_rand($b)] . ' ' . rand(1000, 9999);
}

function limparCookie()
{
    setcookie("login_token", "", time() - 3600, "/");
}