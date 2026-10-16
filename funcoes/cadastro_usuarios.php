<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastro_usuario'])) {

    $nome = trim($_POST['nome'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $id_perfil = (int) ($_POST['id_perfil'] ?? 3);
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $usuario === '' || $email === '' || $cpf === '' || $telefone === '' || $senha === '') {
        $mensagemErro = 'Preencha todos os campos obrigatórios.';
    } else {

        $verifica = mysqli_prepare(
            $conexao,
            'SELECT id FROM usuarios WHERE usuario = ? OR email = ? OR cpf = ?'
        );

        mysqli_stmt_bind_param($verifica, 'sss', $usuario, $email, $cpf);
        mysqli_stmt_execute($verifica);
        $resultadoBusca = mysqli_stmt_get_result($verifica);

        if (mysqli_num_rows($resultadoBusca) > 0) {
            $mensagemErro = 'Usuário, e-mail ou CPF já cadastrados.';
        } else {

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $statusAtivo = 'Ativo';

            $stmt = mysqli_prepare(
                $conexao,
                'INSERT INTO usuarios 
                (nome, usuario, email, cpf, telefone, senha, id_perfil, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );

            mysqli_stmt_bind_param(
                $stmt,
                'ssssssis',
                $nome,
                $usuario,
                $email,
                $cpf,
                $telefone,
                $senhaHash,
                $id_perfil,
                $statusAtivo
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