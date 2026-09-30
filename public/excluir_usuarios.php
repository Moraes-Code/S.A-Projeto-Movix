<?php
session_start();
require_once '../infra/conexao.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idExcluir = intval($_GET['id']);

    $stmt = mysqli_prepare($conexao, "DELETE FROM usuarios WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $idExcluir);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: usuarios.php?msg=sucesso");
        exit();
    }
}

header("Location: usuarios.php");
exit();
?>