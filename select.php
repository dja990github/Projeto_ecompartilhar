<?php
require_once __DIR__ . '/factory/conexao.php';

try {
    // pega a conexão
    $conn = Caminho::getConn();

    // executa a query
    $stmt = $conn->query("SELECT * FROM tbusuarios");

    // pega todos os resultados
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$dados) {
        echo "Nenhum registro encontrado.";
        exit;
    }


    echo "<table style='
    border-collapse: collapse;
    width: 100%;
    font-family: Arial, sans-serif;
    table-layout: fixed;
'>";

    // Cabeçalho
    echo "<tr style='background-color: #f2f2f2;'>";

    $colunas = array_keys($dados[0]);
    $qtdColunas = count($colunas);

    foreach ($colunas as $coluna) {
        echo "<th style='
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
        width: " . (100 / $qtdColunas) . "%;
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    '>$coluna</th>";
    }

    echo "</tr>";

    // Dados
    foreach ($dados as $linha) {
        echo "<tr>";

        foreach ($linha as $valor) {
            echo "<td style='
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        '>$valor</td>";
        }

        echo "</tr>";
    }

    echo "</table>";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}