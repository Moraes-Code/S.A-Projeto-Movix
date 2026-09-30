
<?php
session_start();
require_once '../infra/conexao.php';

$sql = "SELECT * FROM usuarios";
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
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
<div class="sidebar">
<div class="logo-area">
    <img src="../assets/imgs/logo_movix_semfundoazul.png">
    <h3>MOVIX</h3>
    <small>Centro Ferroviário</small>
</div>
<ul>
    <li>
        <a href="dashboard_usuario.php">Dashboard</a>
    </li>

    <li>
        <a href="monitoramento.php">Monitoramento</a>
    </li>

    <li>
        <a href="alertas.php">Alertas</a>
    </li>

    <li>
        <a href="sensores.php">Sensores</a>
    </li>

    <li>
        <a href="trens.php">Trens</a>
    </li>

    <li>
        <a href="usuarios.php">Usuários</a>
    </li>

    <li>
        <a href="relatorios.php">Relatórios</a>
    </li>

    <li>
        <a href="Rotas.php">Rotas</a>
    </li>
    <div class="botao-sair">
    <a href="cadastro.php">
        Sair
    </a>
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
        <div >
            <h4>Usuários Cadastrados</h4>
            <button>
                Novo Usuário
            </button>
        </div>
        <div class="row mb-4">
            <div class="col-md-8">
                <input
                    type="text"
                    placeholder="Pesquisar usuário">
            </div>
            <div class="col-md-4">
                <select >
                    <option>Todos os Perfis</option>
                    <option>Administrador</option>
                    <option>Supervisor</option>
                    <option>Operador</option>
                </select>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
                        <?php 
                            // Tenta pegar o nome direto do banco ou extrai do e-mail
                            if (!empty($usuario['nome'])) {
                                $nomeExibir = $usuario['nome'];
                            } else {
                                $partesEmail = explode('@', $usuario['email']);
                                $nomeExibir  = ucfirst(str_replace(['.', '_'], ' ', $partesEmail[0]));
                            }

                            $perfilExibir = $usuario['perfil'] ?? 'Operador';
                            $statusExibir = $usuario['status'] ?? 'Ativo';
                        ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($nomeExibir) ?></strong>
                            </td>
                            <td>
                                <?= htmlspecialchars($usuario['email'] ?? '') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($usuario['telefone'] ?? '') ?>
                            </td>
                            <td>
                                <span>
                                    <?= htmlspecialchars($perfilExibir) ?>
                                </span>
                            </td>
                            <td>
                                <span>
                                    <?= htmlspecialchars($statusExibir) ?>
                                </span>
                            </td>
                            <td>
                                <button>
                                    Editar
                                </button>
                                <a href="excluir_usuarios.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir o usuário <?= htmlspecialchars($nomeExibir) ?>?');">
                                    Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Nenhum usuário encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</body>
</html>
