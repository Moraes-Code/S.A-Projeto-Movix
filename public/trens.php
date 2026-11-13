<?php
session_start();
require_once '../infra/conexao.php';
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}
$mensagemSucesso = '';
$mensagemErro = '';
if (isset($_SESSION['mensagem_sucesso'])) {
    $mensagemSucesso = $_SESSION['mensagem_sucesso'];
    unset($_SESSION['mensagem_sucesso']);
}
$perfil = (int) ($_SESSION['id_perfil'] ?? 3);
$podeGerenciar = ($perfil === 1);
if (!isset($_SESSION['token_trens'])) {
    $_SESSION['token_trens'] = bin2hex(random_bytes(32));
}
function limpar($texto)
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}
// Atualizar, excluir e editar trem
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$podeGerenciar || !isset($_POST['token']) || !hash_equals($_SESSION['token_trens'], $_POST['token'])) {
        exit('Acesso não permitido.');
    }
    try {
        if (isset($_POST['excluir_trem'])) {
            $id = (int) $_POST['id'];
            $sql = $conexao->prepare("DELETE FROM trens WHERE id = ?");
            $sql->bind_param('i', $id);
            $sql->execute();
            $sql->close();
            $_SESSION['notificacao'] = [
                'tipo' => 'danger',
                'mensagem' => 'Trem excluído com sucesso!'
            ];
        } elseif (isset($_POST['editar_trem'])) {
            $id = (int) $_POST['id'];
            $codigo = trim($_POST['codigo'] ?? '');
            $nome = trim($_POST['nome'] ?? '');
            $linha = trim($_POST['linha'] ?? '');
            $destino = trim($_POST['destino'] ?? '');
            $velocidade = (int) ($_POST['velocidade'] ?? 0);
            $status = (int) ($_POST['status'] ?? 3);
            if ($codigo == '' || $nome == '' || $linha == '' || $destino == '') {
                throw new Exception('Preencha todos os campos.');
            }
            if (!in_array($status, [1, 2, 3], true)) {
                throw new Exception('Status inválido.');
            }
            $sql = $conexao->prepare("UPDATE trens SET codigo = ?, nome = ?, linha = ?, destino = ?, velocidade = ?, status = ? WHERE id = ?");
            $sql->bind_param("ssssiii", $codigo, $nome, $linha, $destino, $velocidade, $status, $id);
            $sql->execute();
            $sql->close();
            $_SESSION['notificacao'] = [
                'tipo' => 'success',
                'mensagem' => 'Trem atualizado com sucesso!'
            ];
        }
        header('Location: trens.php');
        exit;
    } catch (mysqli_sql_exception $erro) {
        $_SESSION['notificacao'] = [
            'tipo' => 'danger',
            'mensagem' => ($erro->getCode() == 1062) ? 'Esse código de trem já está sendo utilizado.' : 'Erro ao realizar a operação.'
        ];
        header('Location: trens.php');
        exit;
    } catch (Exception $erro) {
        $_SESSION['notificacao'] = [
            'tipo' => 'warning',
            'mensagem' => $erro->getMessage()
        ];
        header('Location: trens.php');
        exit;
    }
}
// Cadastro de trens
if ($podeGerenciar) {
    include("../funcoes/cadastro_trens.php");
}
// Buscar trens
$sql = 'SELECT * FROM trens ORDER BY id DESC';
$resultado = mysqli_query($conexao, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trens - MOVIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php include '../components/sidebar.php'; ?>
    <div class="main">
        <?php include '../components/notificacao.php'; ?>
        <div class="topbar">
            <h3>Gerenciamento de Trens</h3>
        </div>
        <div class="content">
            <?php if ($mensagemErro): ?>
                <div class="alert alert-danger">
                    <?= limpar($mensagemErro) ?>
                </div>
            <?php endif; ?>
            <?php if ($podeGerenciar): ?>
                <div class="table-container mb-4">
                    <h4 class="mb-3">Cadastrar Trem</h4>
                    <?php if (!empty($mensagemSucesso)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= limpar($mensagemSucesso) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    <?php endif; ?>
                    <div class="form-container mb-4">
                        <form method="POST">
                            <input type="hidden" name="cadastro_trem" value="1">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" id="codigo" name="codigo" class="form-control"
                                        placeholder="Digite o Código" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="nome" class="form-label">Nome</label>
                                    <input type="text" id="nome" name="nome" class="form-control"
                                        placeholder="Digite o Nome" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="linha" class="form-label">Linha</label>
                                    <input type="text" id="linha" name="linha" class="form-control"
                                        placeholder="Ex: Norte, Sul, Leste, Oeste" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="destino" class="form-label">Destino</label>
                                    <input type="text" id="destino" name="destino" class="form-control"
                                        placeholder="Ex: São Paulo, Rio de Janeiro, Brasília" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="3">Operando</option>
                                        <option value="2">Manutenção</option>
                                        <option value="1">Fora de serviço</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="velocidade" class="form-label">Velocidade</label>
                                    <input type="number" id="velocidade" name="velocidade" class="form-control"
                                        placeholder="Digite a velocidade" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">Salvar trem</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            <div class="table-container">
                <h4>Frota de Trens</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Linha</th>
                                <th>Destino</th>
                                <th>Velocidade</th>
                                <th>Status</th>
                                <?php if ($podeGerenciar): ?>
                                    <th>Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $contador = 0; ?>
                            <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                                <?php while ($trem = mysqli_fetch_assoc($resultado)): ?>
                                    <?php $classeOculta = ($contador >= 10) ? 'trem-oculto d-none' : ''; ?>
                                    <tr class="<?= $classeOculta ?>">
                                        <td><?= limpar($trem['codigo']) ?></td>
                                        <td><?= limpar($trem['nome']) ?></td>
                                        <td><?= limpar($trem['linha']) ?></td>
                                        <td><?= limpar($trem['destino']) ?></td>
                                        <td><?= (int) $trem['velocidade'] ?> km/h</td>
                                        <td>
                                            <?php
                                            $statusNomes = [
                                                3 => 'Operando',
                                                2 => 'Manutenção',
                                                1 => 'Fora de serviço'
                                            ];
                                            echo $statusNomes[$trem['status']] ?? 'Desconhecido';
                                            ?>
                                        </td>
                                        <?php if ($podeGerenciar): ?>
                                            <td class="text-nowrap">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#trem<?= (int) $trem['id'] ?>">
                                                    Editar
                                                </button>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="token"
                                                        value="<?= limpar($_SESSION['token_trens']) ?>">
                                                    <input type="hidden" name="id" value="<?= (int) $trem['id'] ?>">
                                                    <button type="submit" name="excluir_trem" value="1"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Tem certeza que deseja excluir este trem?');">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php $contador++; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= $podeGerenciar ? 7 : 6 ?>" class="text-center">
                                        Nenhum trem cadastrado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($contador > 10): ?>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-primary" id="btnVerMaisTrens">
                            Ver mais
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($podeGerenciar): ?>
        <?php
        $resultadoModais = mysqli_query($conexao, $sql);
        ?>
        <?php if ($resultadoModais): ?>
            <?php while ($trem = mysqli_fetch_assoc($resultadoModais)): ?>
                <div class="modal fade" id="trem<?= (int) $trem['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Trem</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="token" value="<?= limpar($_SESSION['token_trens']) ?>">
                                    <input type="hidden" name="editar_trem" value="1">
                                    <input type="hidden" name="id" value="<?= (int) $trem['id'] ?>">
                                    <label class="form-label">Código</label>
                                    <input type="text" name="codigo" class="form-control mb-3"
                                        value="<?= limpar($trem['codigo']) ?>" required>
                                    <label class="form-label">Nome</label>
                                    <input type="text" name="nome" class="form-control mb-3" value="<?= limpar($trem['nome']) ?>"
                                        required>
                                    <label class="form-label">Linha</label>
                                    <input type="text" name="linha" class="form-control mb-3" value="<?= limpar($trem['linha']) ?>"
                                        required>
                                    <label class="form-label">Destino</label>
                                    <input type="text" name="destino" class="form-control mb-3"
                                        value="<?= limpar($trem['destino']) ?>" required>
                                    <label class="form-label">Velocidade</label>
                                    <input type="number" name="velocidade" class="form-control mb-3"
                                        value="<?= (int) $trem['velocidade'] ?>" required>
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <?php foreach ([3 => 'Operando', 2 => 'Manutenção', 1 => 'Fora de serviço'] as $valor => $nomeStatus): ?>
                                            <option value="<?= $valor ?>" <?= (int) $trem['status'] === $valor ? 'selected' : '' ?>>
                                                <?= limpar($nomeStatus) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const btnVerMaisTrens = document.getElementById('btnVerMaisTrens');
        if (btnVerMaisTrens) {
            btnVerMaisTrens.addEventListener('click', function () {
                const trensOcultos = document.querySelectorAll('.trem-oculto');
                const estaOculto = trensOcultos[0].classList.contains('d-none');
                trensOcultos.forEach(function (trem) {
                    trem.classList.toggle('d-none');
                });
                btnVerMaisTrens.textContent = estaOculto ? 'Ver menos' : 'Ver mais';
            });
        }
    </script>
</body>

</html>