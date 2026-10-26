
<?php
include("../infra/conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastro_trem'])) {

    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $linha = trim($_POST['linha'] ?? '');
    $destino = trim($_POST['destino'] ?? '');
    $status = (int) ($_POST['status'] ?? 3);
    $velocidade = (int) ($_POST['velocidade'] ?? 0);

    if ($codigo === '' || $nome === '' || $linha === '' || $destino === '') {
        $mensagemErro = 'Preencha todos os campos obrigatórios.';
    } else {

        $verifica = mysqli_prepare(
            $conexao,
            'SELECT id FROM trens WHERE codigo = ?'
        );

        mysqli_stmt_bind_param($verifica, 's', $codigo);
        mysqli_stmt_execute($verifica);

        $resultadoBusca = mysqli_stmt_get_result($verifica);

        if (mysqli_num_rows($resultadoBusca) > 0) {

            $mensagemErro = 'Já existe um trem com esse código.';

        } else {

            $stmt = mysqli_prepare(
                $conexao,
                'INSERT INTO trens (codigo, nome, linha, destino, status, velocidade)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );

            mysqli_stmt_bind_param(
                $stmt,
                'ssssii',
                $codigo,
                $nome,
                $linha,
                $destino,
                $status,
                $velocidade
            );

            if (mysqli_stmt_execute($stmt)) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['mensagem_sucesso'] = 'Trem cadastrado com sucesso!';
                header('Location: trens.php');
                exit;
            } else {
                $mensagemErro = 'Não foi possível cadastrar o trem. Tente novamente.';
            }
                mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($verifica);
    }
}
?>
