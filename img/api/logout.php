<?php
session_start();

// remove cookie
setcookie("login_token", "", time() - 3600, "/");

// limpa sessão
$_SESSION = [];

// destrói sessão de verdade
session_destroy();

// redireciona
header("Location: ../index.php");
exit;