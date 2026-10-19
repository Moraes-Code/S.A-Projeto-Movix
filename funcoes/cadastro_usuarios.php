
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastro_usuario'])) {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || $cpf === '' || $telefone === '' || $senha === '') {
        $mensagemErro = 'Preencha todos os campos obrigatórios.';
    } else {

        $verifica = mysqli_prepare(
            $conexao,
            'SELECT id FROM usuarios WHERE email = ? OR cpf = ?'
        );

        mysqli_stmt_bind_param($verifica, 'ss', $email, $cpf);
        mysqli_stmt_execute($verifica);
        $resultadoBusca = mysqli_stmt_get_result($verifica);

        if (mysqli_num_rows($resultadoBusca) > 0) {
            $mensagemErro = 'E-mail ou CPF já cadastrados.';
        } else {

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare(
                $conexao,
                'INSERT INTO usuarios (nome, email, cpf, telefone, senha)
                 VALUES (?, ?, ?, ?, ?)'
            );

            mysqli_stmt_bind_param(
                $stmt,
                'sssss',
                $nome,
                $email,
                $cpf,
                $telefone,
                $senhaHash
            );

            if (mysqli_stmt_execute($stmt)) {
                header('Location: usuarios.php?msg=sucesso');
                exit;
            } else {
                $mensagemErro = 'Não foi possível cadastrar o usuário. Tente novamente.';
            }
        }
    }
}
?>
