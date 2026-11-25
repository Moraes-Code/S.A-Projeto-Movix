
<?php
session_start();
require_once '../infra/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$perfil = (int) ($_SESSION['id_perfil'] ?? 3);
$podeGerenciar = ($perfil === 1);

if (!isset($_SESSION['token_trens'])) {
    $_SESSION['token_trens'] = bin2hex(random_bytes(32));
}

function limpar($texto) {
    return htmlspecialchars((string) ($texto ?? ''), ENT_QUOTES, 'UTF-8');
}

$statusNomes = [
    3 => 'Operando',
    2 => 'Manutenção',
    1 => 'Fora de serviço'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !$podeGerenciar ||
        !isset($_POST['token']) ||
        !hash_equals($_SESSION['token_trens'], $_POST['token'])
    ) {
        exit('Acesso não permitido.');
    }

    try {

        // Cadastrar trem
        if (isset($_POST['cadastro_trem'])) {

            $codigo = trim($_POST['codigo'] ?? '');
            $nome = trim($_POST['nome'] ?? '');
            $linha = trim($_POST['linha'] ?? '');
            $destino = trim($_POST['destino'] ?? '');
            $velocidade = (int) ($_POST['velocidade'] ?? 0);
            $status = (int) ($_POST['status'] ?? 3);
            $id_usuario = (int) ($_POST['id_usuario'] ?? 0);

            if (
                $codigo == '' ||
                $nome == '' ||
                $linha == '' ||
                $destino == '' ||
                $velocidade < 0 ||
                $id_usuario <= 0
            ) {
                throw new Exception('Preencha todos os campos.');
            }

            if (!in_array($status, [1, 2, 3])) {
                throw new Exception('Status inválido.');
            }

            $sql = $conexao->prepare(
                "SELECT id FROM usuarios WHERE id = ?"
            );
            $sql->bind_param('i', $id_usuario);
            $sql->execute();

            $usuarioExiste = $sql->get_result()->num_rows > 0;
            $sql->close();

            if (!$usuarioExiste) {
                throw new Exception('Selecione um usuário válido.');
            }

            $sql = $conexao->prepare(
                "INSERT INTO trens
                (codigo, nome, linha, destino, status, velocidade, id_usuario)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            $sql->bind_param(
                'ssssiii',
                $codigo,
                $nome,
                $linha,
                $destino,
                $status,
                $velocidade,
                $id_usuario
            );

            $sql->execute();
            $sql->close();

            $_SESSION['notificacao'] = [
                'tipo' => 'success',
                'mensagem' => 'Trem cadastrado com sucesso!'
            ];

            header('Location: trens.php');
            exit;
        }

        // Editar trem
        if (isset($_POST['editar_trem'])) {

            $id = (int) ($_POST['id'] ?? 0);
            $codigo = trim($_POST['codigo'] ?? '');
            $nome = trim($_POST['nome'] ?? '');
            $linha = trim($_POST['linha'] ?? '');
            $destino = trim($_POST['destino'] ?? '');
            $velocidade = (int) ($_POST['velocidade'] ?? 0);
            $status = (int) ($_POST['status'] ?? 0);
            $id_usuario = (int) ($_POST['id_usuario'] ?? 0);

            if (
                $id <= 0 ||
                $codigo == '' ||
                $nome == '' ||
                $linha == '' ||
                $destino == '' ||
                $velocidade < 0 ||
                $id_usuario <= 0
            ) {
                throw new Exception('Preencha todos os campos.');
            }

            if (!in_array($status, [1, 2, 3])) {
                throw new Exception('Status inválido.');
            }

            $sql = $conexao->prepare(
                "SELECT id FROM usuarios WHERE id = ?"
            );
            $sql->bind_param('i', $id_usuario);
            $sql->execute();

            $usuarioExiste = $sql->get_result()->num_rows > 0;
            $sql->close();

            if (!$usuarioExiste) {
                throw new Exception('Selecione um usuário válido.');
            }

            $sql = $conexao->prepare(
                "UPDATE trens
                 SET codigo = ?, nome = ?, linha = ?, destino = ?,
                     velocidade = ?, status = ?, id_usuario = ?
                 WHERE id = ?"
            );

            $sql->bind_param(
                'ssssiiii',
                $codigo,
                $nome,
                $linha,
                $destino,
                $velocidade,
                $status,
                $id_usuario,
                $id
            );

            $sql->execute();
            $sql->close();

            $_SESSION['notificacao'] = [
                'tipo' => 'success',
                'mensagem' => 'Trem atualizado com sucesso!'
            ];

            header('Location: trens.php');
            exit;
        }

        // Excluir trem
        if (isset($_POST['excluir_trem'])) {

            $id = (int) ($_POST['id'] ?? 0);

            $sql = $conexao->prepare(
                "DELETE FROM trens WHERE id = ?"
            );
            $sql->bind_param('i', $id);
            $sql->execute();
            $sql->close();

            $_SESSION['notificacao'] = [
                'tipo' => 'success',
                'mensagem' => 'Trem excluído com sucesso!'
            ];

            header('Location: trens.php');
            exit;
        }

    } catch (mysqli_sql_exception $erro) {

        if ($erro->getCode() == 1062) {
            $mensagem = 'Esse código de trem já está sendo utilizado.';
        } elseif ($erro->getCode() == 1451) {
            $mensagem = 'Não é possível excluir este trem porque ele possui rotas cadastradas.';
        } else {
            $mensagem = 'Não foi possível realizar a operação.';
        }

        $_SESSION['notificacao'] = [
            'tipo' => 'danger',
            'mensagem' => $mensagem
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

// Buscar usuários
$usuariosDisponiveis = $conexao->query(
    "SELECT id, nome FROM usuarios ORDER BY nome"
)->fetch_all(MYSQLI_ASSOC);

// Buscar trens
$sql = $conexao->prepare(
    "SELECT t.*, u.nome AS nome_usuario
     FROM trens t
     INNER JOIN usuarios u ON t.id_usuario = u.id
     ORDER BY t.id DESC"
);

$sql->execute();
$trens = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
$sql->close();

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

        <?php if ($podeGerenciar): ?>

            <div class="table-container mb-4">

                <h4 class="mb-3">Cadastrar Trem</h4>

                <?php if (count($usuariosDisponiveis) == 0): ?>

                    <div class="alert alert-warning">
                        Cadastre um usuário antes de cadastrar um trem.
                    </div>

                <?php else: ?>

                    <div class="form-container">

                        <form method="POST">

                            <input type="hidden" name="cadastro_trem" value="1">
                            <input type="hidden" name="token" value="<?= limpar($_SESSION['token_trens']) ?>">

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Código</label>
                                    <input type="text" name="codigo" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nome</label>
                                    <input type="text" name="nome" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Linha</label>
                                    <input type="text" name="linha" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Destino</label>
                                    <input type="text" name="destino" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Usuário responsável</label>

                                    <select name="id_usuario" class="form-select" required>
                                        <option value="">Selecione um usuário</option>

                                        <?php foreach ($usuariosDisponiveis as $usuario): ?>
                                            <option value="<?= $usuario['id'] ?>">
                                                <?= limpar($usuario['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Status</label>

                                    <select name="status" class="form-select" required>
                                        <option value="3">Operando</option>
                                        <option value="2">Manutenção</option>
                                        <option value="1">Fora de serviço</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Velocidade (km/h)</label>
                                    <input type="number" min="0" name="velocidade" class="form-control" value="0" required>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Salvar trem
                                </button>
                            </div>

                        </form>

                    </div>

                <?php endif; ?>

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
                            <th>Responsável</th>
                            <th>Velocidade</th>
                            <th>Status</th>

                            <?php if ($podeGerenciar): ?>
                                <th>Ações</th>
                            <?php endif; ?>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($trens) > 0): ?>

                            <?php foreach ($trens as $trem): ?>

                                <tr>

                                    <td><?= limpar($trem['codigo']) ?></td>
                                    <td><?= limpar($trem['nome']) ?></td>
                                    <td><?= limpar($trem['linha']) ?></td>
                                    <td><?= limpar($trem['destino']) ?></td>
                                    <td><?= limpar($trem['nome_usuario']) ?></td>
                                    <td><?= (int) $trem['velocidade'] ?> km/h</td>

                                    <td>
                                        <?= limpar($statusNomes[(int) $trem['status']] ?? 'Desconhecido') ?>
                                    </td>

                                    <?php if ($podeGerenciar): ?>

                                        <td class="text-nowrap">

                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trem<?= $trem['id'] ?>">
                                                Editar
                                            </button>

                                            <form method="POST" class="d-inline">

                                                <input type="hidden" name="token" value="<?= limpar($_SESSION['token_trens']) ?>">
                                                <input type="hidden" name="id" value="<?= $trem['id'] ?>">

                                                <button
                                                    type="submit"
                                                    name="excluir_trem"
                                                    value="1"
                                                    class="btn btn-sm btn-danger">
                                                    Excluir
                                                </button>

                                            </form>

                                        </td>

                                    <?php endif; ?>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="<?= $podeGerenciar ? 8 : 7 ?>" class="text-center">
                                    Nenhum trem cadastrado.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php if ($podeGerenciar): ?>

    <?php foreach ($trens as $trem): ?>

        <div class="modal fade" id="trem<?= $trem['id'] ?>" tabindex="-1">

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
                            <input type="hidden" name="id" value="<?= $trem['id'] ?>">

                            <label class="form-label">Código</label>
                            <input type="text" name="codigo" class="form-control mb-3" value="<?= limpar($trem['codigo']) ?>" required>

                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control mb-3" value="<?= limpar($trem['nome']) ?>" required>

                            <label class="form-label">Linha</label>
                            <input type="text" name="linha" class="form-control mb-3" value="<?= limpar($trem['linha']) ?>" required>

                            <label class="form-label">Destino</label>
                            <input type="text" name="destino" class="form-control mb-3" value="<?= limpar($trem['destino']) ?>" required>

                            <label class="form-label">Usuário responsável</label>

                            <select name="id_usuario" class="form-select mb-3" required>

                                <?php foreach ($usuariosDisponiveis as $usuario): ?>

                                    <option
                                        value="<?= $usuario['id'] ?>"
                                        <?= (int) $trem['id_usuario'] === (int) $usuario['id'] ? 'selected' : '' ?>>

                                        <?= limpar($usuario['nome']) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                            <label class="form-label">Velocidade</label>
                            <input type="number" min="0" name="velocidade" class="form-control mb-3" value="<?= (int) $trem['velocidade'] ?>" required>

                            <label class="form-label">Status</label>

                            <select name="status" class="form-select">

                                <?php foreach ($statusNomes as $valor => $nomeStatus): ?>

                                    <option
                                        value="<?= $valor ?>"
                                        <?= (int) $trem['status'] === $valor ? 'selected' : '' ?>>

                                        <?= limpar($nomeStatus) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <button type="submit" class="btn btn-primary">
                                Salvar alterações
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
