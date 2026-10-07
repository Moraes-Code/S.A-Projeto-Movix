<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    $_SESSION = [];
}

include("infra/conexao.php");

if (isset($_SESSION['usuario_id'])) {
    header('Location: public/dashboard_usuario.php');
    exit;
}

$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $mensagemErro = 'Preencha e-mail e senha.';
    } else {
        $stmt = mysqli_prepare($conexao, 'SELECT id, nome, senha, id_perfil FROM usuarios WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($resultado);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = (int) $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['id_perfil'] = (int) $usuario['id_perfil'];
            header('Location: public/dashboard_usuario.php');
            exit;
        }

        $mensagemErro = 'E-mail ou senha inválidos.';
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
    <link rel="stylesheet" href="assets/style/style.css">
</head>

<body>
    <section class="Principal">
        <div class="container">
            <div class="row align-items-center min-vh-100">

                <div class="col-lg-6">

                    <img src="assets/imgs/logo_movix_semfundoazul.png" class="hero-logo mb-4" alt="Logo Movix">

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

                        <?php if ($mensagemErro !== ''): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($mensagemErro) ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </form>
                    <p class="mt-3 text-center">
                        Não tem uma conta?
                        <a href="public/cadastro.php">
                            Criar conta
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</body>

</html>