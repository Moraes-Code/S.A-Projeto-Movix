
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastro_trem'])) {

    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $linha = trim($_POST['linha'] ?? '');
    $destino = trim($_POST['destino'] ?? '');
    $status = (int) ($_POST['status'] ?? 3);
    $velocidade = (int) ($_POST['velocidade'] ?? 0);
    $id_rota = (int) ($_POST['id_rota'] ?? 0);
    $id_usuario = (int) ($_POST['id_usuario'] ?? 0);

    if ($codigo == '' || $nome == '' || $linha == '' || $destino == '' ||
        $id_rota <= 0 || $id_usuario <= 0) {

        $mensagemErro = 'Preencha todos os campos.';

    } elseif (!in_array($status, [1, 2, 3]) || $velocidade < 0) {

        $mensagemErro = 'Status ou velocidade inválidos.';

    } else {

        $sql = $conexao->prepare(
            "SELECT id FROM usuarios WHERE id = ?"
        );
        $sql->bind_param('i', $id_usuario);
        $sql->execute();
        $usuarioExiste = $sql->get_result()->num_rows > 0;
        $sql->close();

        $sql = $conexao->prepare(
            "SELECT id_rota FROM rotas WHERE id_rota = ?"
        );
        $sql->bind_param('i', $id_rota);
        $sql->execute();
        $rotaExiste = $sql->get_result()->num_rows > 0;
        $sql->close();

        if (!$usuarioExiste || !$rotaExiste) {

            $mensagemErro = 'Usuário ou rota não encontrados.';

        } else {

            $sql = $conexao->prepare(
                "INSERT INTO trens
                (codigo, nome, linha, destino, status, velocidade, id_rota, id_usuario)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $sql->bind_param(
                'ssssiiii',
                $codigo, $nome, $linha, $destino,
                $status, $velocidade, $id_rota, $id_usuario
            );

            try {
                $sql->execute();

                $_SESSION['mensagem_sucesso'] = 'Trem cadastrado com sucesso!';
                header('Location: trens.php');
                exit;

            } catch (mysqli_sql_exception $erro) {

                $mensagemErro = $erro->getCode() == 1062
                    ? 'Já existe um trem com esse código.'
                    : 'Não foi possível cadastrar o trem.';
            }

            $sql->close();
        }
    }
}
?>
