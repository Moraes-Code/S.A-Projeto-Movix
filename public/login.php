<?php
session_start();

// Se já estiver logado, redireciona direto para o dashboard
if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit;
}

$erros = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }

    if ($senha === '') {
        $erros[] = 'Informe sua senha.';
    }

    if (empty($erros)) {

        // Ajuste o caminho conforme a estrutura do seu projeto
        require_once __DIR__ . '/../config/db.php'; // deve fornecer $pdo (PDO)

        try {
            $stmt = $pdo->prepare('SELECT id, nome, email, tipo, senha FROM usuarios WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($senha, $usuario['senha'])) {

                // Regenera o ID de sessão para evitar fixation
                session_regenerate_id(true);

                $_SESSION['usuario'] = [
                    'id'    => $usuario['id'],
                    'nome'  => $usuario['nome'],
                    'email' => $usuario['email'],
                    'tipo'  => $usuario['tipo'],
                ];

                header('Location: dashboard.php');
                exit;

            } else {
                $erros[] = 'E-mail ou senha incorretos.';
            }

        } catch (PDOException $e) {
            $erros[] = 'Erro ao acessar o sistema. Tente novamente mais tarde.';
            // Em produção, registre $e->getMessage() em log, não exiba ao usuário.
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Movix</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/style/style.css">

</head>

<body class="login-page">

    <div class="container">

        <div class="row min-vh-100 align-items-center">

            <!-- Lado esquerdo -->

            <div class="col-lg-6">

                <div class="pe-lg-5">

                    <img src="../assets/imgs/logo_movix_semfundoazul.png"
                         class="login-logo mb-4"
                         alt="Movix">

                    <h1 class="login-title">
                        Gestão ferroviária inteligente.
                    </h1>

                    <p class="login-text mt-3">
                        Monitore sensores, gerencie usuários,
                        acompanhe trens e receba alertas em
                        tempo real através da plataforma MOVIX.
                    </p>

                </div>

            </div>

            <!-- Lado direito -->

            <div class="col-lg-5 offset-lg-1">

                <div class="login-card">

                    <h2 class="text-center mb-4">
                        Entrar
                    </h2>

                    <?php if (!empty($erros)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($erros as $erro): ?>
                                    <li><?php echo htmlspecialchars($erro); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">

                        <div class="mb-3">

                            <label class="form-label">
                                E-mail
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   placeholder="Digite seu e-mail"
                                   value="<?php echo htmlspecialchars($email); ?>"
                                   required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">
                                Senha
                            </label>

                            <input type="password"
                                   name="senha"
                                   class="form-control"
                                   placeholder="Digite sua senha"
                                   required>

                        </div>

                        <button type="submit"
                                class="btn btn-movix w-100">

                            Entrar

                        </button>

                    </form>

                    <hr>

                    <div class="text-center">

                        <p class="mb-2">
                            Ainda não possui uma conta?
                        </p>

                        <a href="cadastro.php"
                           class="btn btn-outline-movix">

                            Cadastrar Agora

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>