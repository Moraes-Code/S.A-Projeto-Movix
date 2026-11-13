<?php
session_start();
require_once '../infra/conexao.php';
if (!isset($_SESSION['id_perfil']) || $_SESSION['id_perfil'] != 1) {
    header('Location: dashboard_usuario.php');
    exit;
}
if (!isset($_SESSION['token_usuarios'])) {
    $_SESSION['token_usuarios'] = bin2hex(random_bytes(32));
}
function limpar($texto)
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['excluir_usuario']) || isset($_POST['editar_usuario'])))  {
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token_usuarios'], $_POST['token'])) {
        exit('Acesso não permitido.');
    }
    try {
        if (isset($_POST['excluir_usuario'])) {
            $id = (int) $_POST['id'];
            $sql = $conexao->prepare("DELETE FROM usuarios WHERE id = ?");
            $sql->bind_param('i', $id);
            $sql->execute();
            $sql->close();
            $_SESSION['notificacao'] = ['tipo' => 'danger', 'mensagem' => 'Usuário excluído com sucesso!'];
        } elseif (isset($_POST['editar_usuario'])) {
            $id = (int) $_POST['id'];
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cpf = trim($_POST['cpf'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');
            if ($nome == '' || $email == '' || $cpf == '' || $telefone == '') {
                throw new Exception('Preencha todos os campos.');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Digite um e-mail válido.');
            }
            $sql = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ?, cpf = ?, telefone = ? WHERE id = ?");
            $sql->bind_param('ssssi', $nome, $email, $cpf, $telefone, $id);
            $sql->execute();
            $sql->close();
            $_SESSION['notificacao'] = ['tipo' => 'success', 'mensagem' => 'Usuário atualizado com sucesso!'];
        }
        header('Location: usuarios.php');
        exit;
    } catch (mysqli_sql_exception $erro) {
        $_SESSION['notificacao'] = ['tipo' => 'danger', 'mensagem' => ($erro->getCode() == 1062) ? 'E-mail ou CPF já cadastrado.' : 'Erro ao realizar a operação.'];
        header('Location: usuarios.php');
        exit;
    } catch (Exception $erro) {
        $_SESSION['notificacao'] = ['tipo' => 'warning', 'mensagem' => $erro->getMessage()];
        header('Location: usuarios.php');
        exit;
    }
}
include("../funcoes/cadastro_usuarios.php");
if (isset($_SESSION['mensagem_sucesso'])) {
    $_SESSION['notificacao'] = ['tipo' => 'success', 'mensagem' => $_SESSION['mensagem_sucesso']];
    unset($_SESSION['mensagem_sucesso']);
}
if (isset($_SESSION['mensagem_erro'])) {
    $_SESSION['notificacao'] = ['tipo' => 'danger', 'mensagem' => $_SESSION['mensagem_erro']];
    unset($_SESSION['mensagem_erro']);
}
$sql = "SELECT usuarios.*, perfis.nome AS nome_perfil FROM usuarios LEFT JOIN perfis ON usuarios.id_perfil = perfis.id ORDER BY usuarios.id DESC";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php include '../components/sidebar.php'; ?>
    <div class="main">
        <?php include '../components/notificacao.php'; ?>
        <div class="topbar">
            <h3>Gerenciamento de Usuários</h3>
        </div>
        <div class="content">
            <div class="table-container">
                <h4 class="mb-3">Cadastrar Usuários</h4>
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
                <h4 class="mb-3">Usuários cadastrados</h4>
                <div class="row mb-4">
                    <div class="col-md-8">
                        <input type="text" id="pesquisaUsuario" class="form-control" placeholder="Pesquisar usuário">
                    </div>
                </div>
                <div class="table-responsive">
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
                            <?php $contador = 0; ?>
                            <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                                <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
                                    <?php
                                    $nomeExibir = !empty($usuario['nome']) ? $usuario['nome'] : 'Usuário';
                                    $usuarioExibir = $usuario['usuario'] ?? '';
                                    $cargoExibir = $usuario['nome_perfil'] ?? 'Usuário';
                                    ?>
                                    <tr class="<?= $contador >= 10 ? 'usuario-oculto d-none' : '' ?>">
                                        <td><strong><?= limpar($nomeExibir) ?></strong></td>
                                        <td><?= limpar($usuarioExibir) ?></td>
                                        <td><?= limpar($usuario['email'] ?? '') ?></td>
                                        <td><?= limpar($usuario['cpf'] ?? '') ?></td>
                                        <td><?= limpar($usuario['telefone'] ?? '') ?></td>
                                        <td><?= limpar($cargoExibir) ?></td>
                                        <td class="text-nowrap">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#usuario<?= (int) $usuario['id'] ?>">Editar</button>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="token"
                                                    value="<?= limpar($_SESSION['token_usuarios']) ?>">
                                                <input type="hidden" name="id" value="<?= (int) $usuario['id'] ?>">
                                                <button type="submit" name="excluir_usuario" value="1"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php $contador++; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum usuário encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($contador > 10): ?>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-primary" id="btnVerMais">Ver mais</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    $resultadoModais = mysqli_query($conexao, $sql);
    ?>
    <?php if ($resultadoModais): ?>
        <?php while ($usuario = mysqli_fetch_assoc($resultadoModais)): ?>
            <div class="modal fade" id="usuario<?= (int) $usuario['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Usuário</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="token" value="<?= limpar($_SESSION['token_usuarios']) ?>">
                                <input type="hidden" name="editar_usuario" value="1">
                                <input type="hidden" name="id" value="<?= (int) $usuario['id'] ?>">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control mb-3"
                                    value="<?= limpar($usuario['nome'] ?? '') ?>" required>
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control mb-3"
                                    value="<?= limpar($usuario['email'] ?? '') ?>" required>
                                <label class="form-label">CPF</label>
                                <input type="text" name="cpf" class="form-control mb-3"
                                    value="<?= limpar($usuario['cpf'] ?? '') ?>" required>
                                <label class="form-label">Telefone</label>
                                <input type="tel" name="telefone" class="form-control mb-3"
                                    value="<?= limpar($usuario['telefone'] ?? '') ?>" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const btnVerMais = document.getElementById('btnVerMais');
        if (btnVerMais) {
            btnVerMais.addEventListener('click', function () {
                const usuariosOcultos = document.querySelectorAll('.usuario-oculto');
                usuariosOcultos.forEach(function (usuario) {
                    usuario.classList.toggle('d-none');
                });
                this.innerText = this.innerText === 'Ver mais' ? 'Ver menos' : 'Ver mais';
            });
        }
        const pesquisaUsuario = document.getElementById('pesquisaUsuario');
        if (pesquisaUsuario) {
            pesquisaUsuario.addEventListener('input', function () {
                const termo = this.value.toLowerCase();
                const linhas = document.querySelectorAll('tbody tr');
                linhas.forEach(function (linha) {
                    linha.classList.toggle('d-none', !linha.innerText.toLowerCase().includes(termo));
                });
            });
        }
    </script>
</body>

</html>