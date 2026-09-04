<?php
session_start();

$erros = [];
$sucesso = false;

// Valores enviados (para repopular o formulário em caso de erro)
$nome = '';
$email = '';
$telefone = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $tipo     = trim($_POST['tipo'] ?? '');
    $senha    = $_POST['senha'] ?? '';

    // Validações básicas
    if ($nome === '') {
        $erros[] = 'O nome completo é obrigatório.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }

    if ($telefone === '') {
        $erros[] = 'O telefone é obrigatório.';
    }

    if (!in_array($tipo, ['Administrador', 'Operador', 'Supervisor'], true)) {
        $erros[] = 'Selecione um tipo de usuário válido.';
    }

    if (strlen($senha) < 6) {
        $erros[] = 'A senha deve ter no mínimo 6 caracteres.';
    }

    // Se não houver erros, tenta cadastrar
    if (empty($erros)) {

        // Ajuste o caminho conforme a estrutura do seu projeto
        require_once __DIR__ . '/../config/db.php'; // deve fornecer $pdo (PDO)

        try {
            // Verifica se o e-mail já está cadastrado
            $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email');
            $stmt->execute(['email' => $email]);

            if ($stmt->fetch()) {
                $erros[] = 'Já existe um usuário cadastrado com este e-mail.';
            } else {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare(
                    'INSERT INTO usuarios (nome, email, telefone, tipo, senha, criado_em)
                     VALUES (:nome, :email, :telefone, :tipo, :senha, NOW())'
                );

                $stmt->execute([
                    'nome'     => $nome,
                    'email'    => $email,
                    'telefone' => $telefone,
                    'tipo'     => $tipo,
                    'senha'    => $senhaHash,
                ]);

                $sucesso = true;

                // Limpa os campos após sucesso
                $nome = $email = $telefone = $tipo = '';
            }
        } catch (PDOException $e) {
            $erros[] = 'Erro ao cadastrar usuário. Tente novamente mais tarde.';
            // Em produção, registre $e->getMessage() em log, não exiba ao usuário.
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - MOVIX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body class="login-bg">

    <div class="container">

        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-lg-7">

                <div class="card cadastro-card">

                    <div class="card-body p-5">

                        <div class="text-center mb-4">

                            <h1 class="logo-title">
                                MOVIX
                            </h1>

                            <p class="text-muted">
                                Cadastro de Usuário
                            </p>

                        </div>

                        <?php if ($sucesso): ?>
                            <div class="alert alert-success">
                                Usuário cadastrado com sucesso!
                            </div>
                        <?php endif; ?>

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

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Nome Completo
                                    </label>

                                    <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($nome); ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        E-mail
                                    </label>

                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Telefone
                                    </label>

                                    <input type="text" name="telefone" class="form-control" value="<?php echo htmlspecialchars($telefone); ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Tipo de Usuário
                                    </label>

                                    <select name="tipo" class="form-select" required>
                                        <option value="" disabled <?php echo $tipo === '' ? 'selected' : ''; ?>>Selecione</option>
                                        <option value="Administrador" <?php echo $tipo === 'Administrador' ? 'selected' : ''; ?>>Administrador</option>
                                        <option value="Operador" <?php echo $tipo === 'Operador' ? 'selected' : ''; ?>>Operador</option>
                                        <option value="Supervisor" <?php echo $tipo === 'Supervisor' ? 'selected' : ''; ?>>Supervisor</option>
                                    </select>

                                </div>

                            </div>

                            <div class="mb-4">

                                <label class="form-label">
                                    Senha
                                </label>

                                <input type="password" name="senha" class="form-control" required minlength="6">

                            </div>

                            <div class="d-grid">

                                <button type="submit" class="btn btn-movix">
                                    Cadastrar Usuário
                                </button>

                            </div>

                            <div class="text-center mt-3">

                                <a href="login.php">
                                    Voltar para Login
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>