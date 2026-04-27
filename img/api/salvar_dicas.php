<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once "../factory/conexao.php";

// pega conexão PDO
$conn = Caminho::getConn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = trim($_POST['titulo'] ?? '');
    $dica = trim($_POST['dica'] ?? '');

    if ($titulo && $dica) {

        $sql = "INSERT INTO tbdicas (titulo, dica) VALUES (:titulo, :dica)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":titulo", $titulo);
        $stmt->bindParam(":dica", $dica);

        if ($stmt->execute()) {
            echo "<script>alert('Sucesso!')</script>";
        } else {
            echo "<script>alert('Erro ao salvar')</script>";
        }
        header('Location: ../view/dicas/addicas.php');
    } else {
        echo "CAMPOS VAZIOS";
    }
}