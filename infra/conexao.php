<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "movix";

$conexao = new mysqli($host, $usuario, $senha, $banco, 3308);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");

?>