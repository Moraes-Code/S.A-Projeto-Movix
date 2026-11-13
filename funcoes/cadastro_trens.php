<?php
// Este arquivo é incluído por public/trens.php.
// A sessão e a conexão já foram iniciadas na página principal.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastro_trem'])) {
    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $linha = trim($_POST['linha'] ?? '');
    $destino = trim($_POST['destino'] ?? '');
    $status = (int) ($_POST['status'] ?? 3);
    $velocidade = (int) ($_POST['velocidade'] ?? 0);
    $id_rota = (int) ($_POST['id_rota'] ?? 0);

    if ($codigo === '' || $nome === '' || $linha === '' || $destino === '' || $id_rota <= 0) {
        $mensagemErro = 'Preencha todos os campos e selecione uma rota.';
    } elseif (!in_array($status, [1, 2, 3], true) || $velocidade < 0) {
        $mensagemErro = 'Status ou velocidade inválidos.';
    } else {
        $verificaRota = $conexao->prepare("SELECT id_rota FROM rotas WHERE id_rota = ?");
        $verificaRota->bind_param('i', $id_rota);
        $verificaRota->execute();
        $rotaExiste = $verificaRota->get_result()->num_rows > 0;
        $verificaRota->close();

        if (!$rotaExiste) {
            $mensagemErro = 'A rota selecionada não existe. Atualize a página e tente novamente.';
        } else {
            $stmt = $conexao->prepare(
                "INSERT INTO trens (codigo, nome, linha, destino, status, velocidade, id_rota)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('ssssiii', $codigo, $nome, $linha, $destino, $status, $velocidade, $id_rota);

            try {
                if ($stmt->execute()) {
                    $_SESSION['mensagem_sucesso'] = 'Trem cadastrado com sucesso!';
                    header('Location: trens.php');
                    exit;
                }
            } catch (mysqli_sql_exception $erro) {
                $mensagemErro = ($erro->getCode() == 1062)
                    ? 'Já existe um trem com esse código.'
                    : 'Não foi possível cadastrar o trem.';
            }
            $stmt->close();
        }
    }
}
?>