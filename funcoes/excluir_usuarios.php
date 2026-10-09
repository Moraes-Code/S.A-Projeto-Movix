```php
<?php

session_start();
require_once '../infra/conexao.php';

// Somente administradores podem excluir usuários
if (!isset($_SESSION['id_perfil']) || (int)$_SESSION['id_perfil'] !== 1) {
    header('Location: ../public/dashboard_usuario.php');
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $idExcluir = intval($_GET['id']);

    if ($idExcluir <= 0) {
        header('Location: ../public/usuarios.php?msg=erro');
        exit();
    }

    $stmtVerifica = mysqli_prepare(
        $conexao,
        "SELECT id FROM usuarios WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmtVerifica, "i", $idExcluir);
    mysqli_stmt_execute($stmtVerifica);

    $resultado = mysqli_stmt_get_result($stmtVerifica);

    if (mysqli_num_rows($resultado) == 0) {
        header('Location: ../public/usuarios.php?msg=nao_encontrado');
        exit();
    }

    $stmt = mysqli_prepare(
        $conexao,
        "DELETE FROM usuarios WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $idExcluir);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../public/usuarios.php?msg=excluido');
        exit();
    } else {
        header('Location: ../public/usuarios.php?msg=erro');
        exit();
    }
}

header('Location: ../public/usuarios.php?msg=erro');
exit();

?>
```
