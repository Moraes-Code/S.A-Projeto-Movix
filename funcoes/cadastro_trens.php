<?php

include("../infra/conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastro_usuario'])) {

    $código = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $linha = trim($_POST['linha'] ?? '');
    $destino = trim($_POST['destino'] ?? '');
    $status = (int) ($_POST['status'] ?? 3);
    $velocidade = (int) ($_POST['velocidade'] ?? 0);



        $verifica = mysqli_prepare(
            $conexao,
            'SELECT id FROM trens WHERE codigo = ? OR nome = ? OR linha = ?'
        );

        mysqli_stmt_bind_param(
            $verifica,
            'sss',
            $código,
            $nome,
            $linha
        );

        mysqli_stmt_execute($verifica);
        $resultadoBusca = mysqli_stmt_get_result($verifica);

        if (mysqli_num_rows($resultadoBusca) > 0) {
            $mensagemErro = 'Trem já cadastrado.';
        } else {

            $stmt = mysqli_prepare(
                $conexao,
                'INSERT INTO trens 
                (codigo, nome, linha, destino, status, velocidade) 
                VALUES (?, ?, ?, ?, ?, ?)'
            );

            mysqli_stmt_bind_param(
                $stmt,
                'ssssii',
                $código,
                $nome,
                $linha,
                $destino,
                $status,
                $velocidade
            );

            if (mysqli_stmt_execute($stmt)) {
                header('Location: trens.php?msg=sucesso');
                exit;
            } else {
                $mensagemErro = 'Não foi possível cadastrar o trem. Tente novamente.';
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($verifica);
    }
?>