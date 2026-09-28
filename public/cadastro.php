<?php

include("../infra/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (email, senha, cpf, telefone)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $email,
        $senhaHash,
        $cpf,
        $telefone
    );

    if ($stmt->execute()) {

        header("Location: ../index.php");
        exit;

    } else {

        echo "Erro ao criar conta: " . $stmt->error;

    }

    $stmt->close();
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

                    <h1>
                        Gestão ferroviária
                    </h1>

                    <p class="hero-description mt-4">
                        O Movix é uma plataforma desenvolvida para monitorar,
                        gerenciar e controlar operações ferroviárias em tempo
                        real, oferecendo maior segurança, eficiência e tomada
                        de decisão baseada em dados.
                    </p>
                    <form method="POST" class="mt-4">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                E-mail
                            </label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Digite seu e-mail" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">
                                Senha
                            </label>
                            <input type="password" id="senha" name="senha" class="form-control"
                                placeholder="Digite sua senha" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">
                                CPF
                            </label>
                            <input type="password" id="CPF" name="cpf" class="form-control"
                                placeholder="Digite seu CPF" required>
                        </div>
                        <div class="mb-3">
                            <label for="Telefone" class="form-label">
                                Telefone
                            </label>
                            <input type="tel" id="telefone" name="telefone" class="form-control"
                                placeholder="Ex: (99)9999-9999" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </form>

                </div>
            </div>
        </div>
    </section>
</body>

</html>