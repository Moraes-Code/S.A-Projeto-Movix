
<?php
session_start();
require_once '../infra/conexao.php';

if (!isset($_SESSION['id_perfil']) || (int) $_SESSION['id_perfil'] !== 1) {
    header('Location: ../public/trens.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idExcluir = (int) $_GET['id'];

    if ($idExcluir <= 0) {
        header('Location: ../public/sensores.php?msg=erro');
        exit;
    }

    $stmt = mysqli_prepare(
        $conexao,
        "DELETE FROM sensores WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $idExcluir);

    if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
        header('Location: ../public/sensores.php?msg=excluido');
        exit;
    }

    header('Location: ../public/sensores.php?msg=erro');
    exit;
}

header('Location: ../public/sensores.php?msg=erro');
exit;
?>