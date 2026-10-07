<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$cargo = $_SESSION['usuario_cargo'] ?? '';

if ($cargo !== 'Administrador') {
    header('Location: dashboard_usuario.php');
    exit;
}

?>