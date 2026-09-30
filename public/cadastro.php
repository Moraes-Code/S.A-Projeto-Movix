<?php

include("../infra/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $senha = $_POST["senha"] ?? '';
    $cpf = trim($_POST["cpf"] ?? '');
    $telefone = trim($_POST["telefone"] ?? '');

    if ($nome === '' || $email === '' || $senha === '' || $cpf === '' || $telefone === '') {
        echo "Preencha todos os campos obrigatórios.";
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $verifica = mysqli_prepare($conexao, "SELECT id FROM usuarios WHERE email = ? OR cpf = ?");
        mysqli_stmt_bind_param($verifica, "ss", $email, $cpf);
        mysqli_stmt_execute($verifica);
        $resultado = mysqli_stmt_get_result($verifica);

        if (mysqli_num_rows($resultado) > 0) {
            echo "Já existe um usuário com este e-mail ou CPF.";
        } else {
            $sql = "INSERT INTO usuarios (nome, email, senha, cpf, telefone) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("sssss", $nome, $email, $senhaHash, $cpf, $telefone);

            if ($stmt->execute()) {
                header("Location: ../index.php");
                exit;
            } else {
                echo "Erro ao criar conta: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movix - Gestão Ferroviária Inteligente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <section class="Principal">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <img src="../assets/imgs/logo_movix_semfundoazul.png" class="hero-logo mb-4" alt="Logo Movix">
                    <h1>Gestão ferroviária</h1>
                    <p class="hero-description mt-4">
                        O Movix é uma plataforma desenvolvida para monitorar,
                        gerenciar e controlar operações ferroviárias em tempo
                        real, oferecendo maior segurança, eficiência e tomada
                        de decisão baseada em dados.
                    </p>
                    <form method="POST" class="mt-4">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite seu nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Digite seu e-mail" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha" required>
                        </div>
                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" id="cpf" name="cpf" class="form-control" placeholder="Digite seu CPF" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" class="form-control" placeholder="Ex: (99) 99999-9999" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </form>
                    <p class="text-center mt-3">
                        Já tem uma conta? <a href="../index.php">Fazer login</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</body>

</html>