<?php
session_start();
require_once '../infra/conexao.php';
// Somente administradores podem excluir rotas
if (!isset($_SESSION['id_perfil']) || (int)$_SESSION['id_perfil'] !== 1) {
    header('Location: ../public/dashboard_usuario.php');
    exit();
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idExcluir = intval($_GET['id']);
    if ($idExcluir <= 0) {
        header('Location: ../public/rotas.php?msg=erro');
        exit();
    }
    $stmtVerifica = mysqli_prepare(
        $conexao,
        "SELECT id_rota FROM rotas WHERE id_rota = ?"
    );
    mysqli_stmt_bind_param($stmtVerifica, "i", $idExcluir);
    mysqli_stmt_execute($stmtVerifica);
    $resultado = mysqli_stmt_get_result($stmtVerifica);
    if (mysqli_num_rows($resultado) == 0) {
        mysqli_stmt_close($stmtVerifica);
        header('Location: ../public/rotas.php?msg=nao_encontrado');
        exit();
    }
    mysqli_stmt_close($stmtVerifica);
    $stmtExcluir = mysqli_prepare(
        $conexao,
        "DELETE FROM rotas WHERE id_rota = ?"
    );
    mysqli_stmt_bind_param($stmtExcluir, "i", $idExcluir);
    if (mysqli_stmt_execute($stmtExcluir)) {
        mysqli_stmt_close($stmtExcluir);
        header('Location: ../public/rotas.php?msg=excluido');
        exit();
    } else {
        mysqli_stmt_close($stmtExcluir);
        header('Location: ../public/rotas.php?msg=erro');
        exit();
    }
}
header('Location: ../public/rotas.php?msg=erro');
exit();
?>