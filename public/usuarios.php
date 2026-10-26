<?php
session_start();
require_once '../infra/conexao.php';

if (!isset($_SESSION['id_perfil']) || $_SESSION['id_perfil'] != 1) {
    header('Location: dashboard_usuario.php');
    exit;
}

$mensagemSucesso = '';
$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    $id = (int) $_POST['id'];
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    $sql = "UPDATE usuarios
        SET nome = ?, email = ?, cpf = ?, telefone = ?
        WHERE id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param(
        "ssssi",
        $nome,
        $email,
        $cpf,
        $telefone,
        $id
    );

    if ($stmt->execute()) {
        header('Location: usuarios.php');
        exit;
    } else {
        $mensagemErro = 'Erro ao atualizar usuário.';
    }
}

include("../funcoes/cadastro_usuarios.php");

if (isset($_SESSION['mensagem_sucesso'])) {
    $mensagemSucesso = $_SESSION['mensagem_sucesso'];
    unset($_SESSION['mensagem_sucesso']);
}

if (isset($_SESSION['mensagem_erro'])) {
    $mensagemErro = $_SESSION['mensagem_erro'];
    unset($_SESSION['mensagem_erro']);
}

$sql = 'SELECT usuarios.*, perfis.nome AS nome_perfil
        FROM usuarios
        LEFT JOIN perfis ON usuarios.id_perfil = perfis.id
        ORDER BY usuarios.id DESC';

$resultado = mysqli_query($conexao, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - MOVIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <div class="sidebar">
        <div class="logo-area">
            <img src="../assets/imgs/logo_movix_semfundoazul.png" alt="Logo MOVIX">
            <h3>MOVIX</h3>
            <small>Centro Ferroviário</small>
        </div>
        <ul>
            <li><a href="dashboard_usuario.php">Dashboard</a></li>
            <li><a href="monitoramento.php">Monitoramento</a></li>
            <li><a href="alertas.php">Alertas</a></li>
            <li><a href="sensores.php">Sensores</a></li>
            <li><a href="trens.php">Trens</a></li>
            <li><a href="usuarios.php">Usuários</a></li>
            <li><a href="relatorios.php">Relatórios</a></li>
            <li><a href="Rotas.php">Rotas</a></li>
            <div class="botao-sair">
                <a href="../index.php?logout=1">Sair</a>
            </div>
        </ul>
    </div>
    <div class="main">
        <div class="topbar">
            <div>
                <h3>Gerenciamento de Usuários</h3>
            </div>
        </div>
        <div class="content">
            <div class="table-container">
                <h4 class="mb-3">Cadastrar Usuarios</h4>
                <?php if (!empty($mensagemSucesso)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($mensagemSucesso) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensagemErro)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($mensagemErro) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif; ?>

                <div class="form-container mb-4">
                    <form method="POST">
                        <input type="hidden" name="cadastro_usuario" value="1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" id="nome" name="nome" class="form-control"
                                    placeholder="Digite o nome" required>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario" class="form-label">Usuário</label>
                                <input type="text" id="usuario" name="usuario" class="form-control"
                                    placeholder="Digite o usuário" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Digite o e-mail" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" id="cpf" name="cpf" class="form-control" placeholder="Digite o CPF"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="tel" id="telefone" name="telefone" class="form-control"
                                    placeholder="(99) 99999-9999" required>
                            </div>
                            <div class="col-md-4">
                                <label for="id_perfil" class="form-label">Perfil</label>
                                <select id="id_perfil" name="id_perfil" class="form-control">
                                    <option value="3">Usuário</option>
                                    <option value="2">Operário</option>
                                    <option value="1">Administrador</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" id="senha" name="senha" class="form-control"
                                    placeholder="Digite a senha" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Salvar usuário</button>
                        </div>
                    </form>
                </div>

                <div class="row mb-4">
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="Pesquisar usuário">
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Usuário</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Cargo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                            <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
                                <?php
                                $nomeExibir = !empty($usuario['nome']) ? $usuario['nome'] : 'Usuário';
                                $usuarioExibir = $usuario['usuario'] ?? '';
                                $cargoExibir = $usuario['nome_perfil'] ?? 'Usuário';
                                ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($nomeExibir) ?></strong></td>
                                    <td><?= htmlspecialchars($usuarioExibir) ?></td>
                                    <td><?= htmlspecialchars($usuario['email'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($usuario['cpf'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($usuario['telefone'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($cargoExibir) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalUsuario" data-id="<?= $usuario['id'] ?>"
                                            data-nome="<?= htmlspecialchars($usuario['nome'] ?? '', ENT_QUOTES) ?>"
                                            data-email="<?= htmlspecialchars($usuario['email'] ?? '', ENT_QUOTES) ?>"
                                            data-cpf="<?= htmlspecialchars($usuario['cpf'] ?? '', ENT_QUOTES) ?>"
                                            data-telefone="<?= htmlspecialchars($usuario['telefone'] ?? '', ENT_QUOTES) ?>">
                                            Editar
                                        </button>
                                        <a href="../funcoes/excluir_usuarios.php?id=<?= (int) ($usuario['id'] ?? 0) ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem certeza que deseja excluir o usuário <?= htmlspecialchars($nomeExibir) ?>?');">
                                            Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Nenhum usuário encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include '../components/modal-usuario.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>