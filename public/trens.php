<?php
session_start();
require_once '../infra/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$perfil = (int) ($_SESSION['id_perfil'] ?? 3);
$podeGerenciar = ($perfil === 1);
$mensagemSucesso = $_SESSION['mensagem_sucesso'] ?? '';
unset($_SESSION['mensagem_sucesso']);

if (!isset($_SESSION['token_trens'])) {
    $_SESSION['token_trens'] = bin2hex(random_bytes(32));
}

function limpar($texto)
{
    return htmlspecialchars((string) ($texto ?? ''), ENT_QUOTES, 'UTF-8');
}

$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$podeGerenciar || !isset($_POST['token']) ||
        !hash_equals($_SESSION['token_trens'], $_POST['token'])) {
        exit('Acesso não permitido.');
    }

    try {
        if (isset($_POST['excluir_trem'])) {
            $id = (int) ($_POST['id'] ?? 0);
            $sql = $conexao->prepare("DELETE FROM trens WHERE id = ?");
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

        if (isset($_POST['editar_trem'])) {
            $id = (int) ($_POST['id'] ?? 0);
            $codigo = trim($_POST['codigo'] ?? '');
            $nome = trim($_POST['nome'] ?? '');
            $linha = trim($_POST['linha'] ?? '');
            $destino = trim($_POST['destino'] ?? '');
            $velocidade = (int) ($_POST['velocidade'] ?? 0);
            $status = (int) ($_POST['status'] ?? 0);
            $id_rota = (int) ($_POST['id_rota'] ?? 0);

            if ($id <= 0 || $codigo === '' || $nome === '' || $linha === '' ||
                $destino === '' || $id_rota <= 0 || $velocidade < 0) {
                throw new Exception('Preencha todos os campos corretamente.');
            }

            if (!in_array($status, [1, 2, 3], true)) {
                throw new Exception('Status inválido.');
            }

            $verifica = $conexao->prepare("SELECT id_rota FROM rotas WHERE id_rota = ?");
            $verifica->bind_param('i', $id_rota);
            $verifica->execute();
            $rotaExiste = $verifica->get_result()->num_rows > 0;
            $verifica->close();

            if (!$rotaExiste) {
                throw new Exception('A rota selecionada não existe.');
            }

            $sql = $conexao->prepare(
                "UPDATE trens
                 SET codigo=?, nome=?, linha=?, destino=?, velocidade=?, status=?, id_rota=?
                 WHERE id=?"
            );
            $sql->bind_param(
                'ssssiiii',
                $codigo, $nome, $linha, $destino, $velocidade, $status, $id_rota, $id
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
    } catch (mysqli_sql_exception $erro) {
        $_SESSION['notificacao'] = [
            'tipo' => 'danger',
            'mensagem' => ($erro->getCode() == 1062)
                ? 'Esse código de trem já está sendo utilizado.'
                : 'Não foi possível realizar a operação. Verifique se existem sensores vinculados ao trem.'
        ];
        header('Location: trens.php');
        exit;
    } catch (Exception $erro) {
        $_SESSION['notificacao'] = ['tipo' => 'warning', 'mensagem' => $erro->getMessage()];
        header('Location: trens.php');
        exit;
    }
}

$rotasDisponiveis = [];
$resultadoRotas = $conexao->query(
    "SELECT id_rota, nome_rota, codigo_rota FROM rotas ORDER BY nome_rota"
);
if ($resultadoRotas) {
    $rotasDisponiveis = $resultadoRotas->fetch_all(MYSQLI_ASSOC);
}

if ($podeGerenciar) {
    include '../funcoes/cadastro_trens.php';
}

$sql = $conexao->prepare(
    "SELECT t.*, r.nome_rota, r.codigo_rota
     FROM trens t
     INNER JOIN rotas r ON t.id_rota = r.id_rota
     ORDER BY t.id DESC"
);
$sql->execute();
$trens = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
$sql->close();

$statusNomes = [3 => 'Operando', 2 => 'Manutenção', 1 => 'Fora de serviço'];
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
        <div class="topbar"><h3>Gerenciamento de Trens</h3></div>
        <div class="content">
            <?php if ($mensagemErro !== ''): ?>
                <div class="alert alert-danger"><?= limpar($mensagemErro) ?></div>
            <?php endif; ?>

            <?php if ($mensagemSucesso !== ''): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= limpar($mensagemSucesso) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($podeGerenciar): ?>
                <div class="table-container mb-4">
                    <h4 class="mb-3">Cadastrar Trem</h4>
                    <div class="form-container mb-4">
                        <?php if (count($rotasDisponiveis) === 0): ?>
                            <div class="alert alert-warning">Cadastre uma rota antes de cadastrar um trem.</div>
                        <?php else: ?>
                            <form method="POST">
                                <input type="hidden" name="cadastro_trem" value="1">
                                <input type="hidden" name="token" value="<?= limpar($_SESSION['token_trens']) ?>">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="codigo">Código</label>
                                        <input type="text" id="codigo" name="codigo" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="nome">Nome</label>
                                        <input type="text" id="nome" name="nome" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="linha">Linha</label>
                                        <input type="text" id="linha" name="linha" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="destino">Destino</label>
                                        <input type="text" id="destino" name="destino" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="id_rota">Rota relacionada</label>
                                        <select id="id_rota" name="id_rota" class="form-select" required>
                                            <option value="">Selecione uma rota</option>
                                            <?php foreach ($rotasDisponiveis as $rota): ?>
                                                <option value="<?= (int) $rota['id_rota'] ?>">
                                                    <?= limpar($rota['codigo_rota'] . ' - ' . $rota['nome_rota']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="status">Status</label>
                                        <select id="status" name="status" class="form-select">
                                            <option value="3">Operando</option>
                                            <option value="2">Manutenção</option>
                                            <option value="1">Fora de serviço</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="velocidade">Velocidade (km/h)</label>
                                        <input type="number" min="0" id="velocidade" name="velocidade" class="form-control" value="0" required>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">Salvar trem</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <h4>Frota de Trens</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Código</th><th>Nome</th><th>Linha</th><th>Destino</th>
                                <th>Rota</th><th>Velocidade</th><th>Status</th>
                                <?php if ($podeGerenciar): ?><th>Ações</th><?php endif; ?>
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
                                        <td><?= limpar($trem['codigo_rota'] . ' - ' . $trem['nome_rota']) ?></td>
                                        <td><?= (int) $trem['velocidade'] ?> km/h</td>
                                        <td><?= limpar($statusNomes[(int) $trem['status']] ?? 'Desconhecido') ?></td>
                                        <?php if ($podeGerenciar): ?>
                                            <td class="text-nowrap">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#trem<?= (int) $trem['id'] ?>">Editar</button>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="token" value="<?= limpar($_SESSION['token_trens']) ?>">
                                                    <input type="hidden" name="id" value="<?= (int) $trem['id'] ?>">
                                                    <button type="submit" name="excluir_trem" value="1" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Tem certeza que deseja excluir este trem?');">Excluir</button>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="<?= $podeGerenciar ? 8 : 7 ?>" class="text-center">Nenhum trem cadastrado.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if ($podeGerenciar): ?>
        <?php foreach ($trens as $trem): ?>
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
                                <input type="text" name="codigo" class="form-control mb-3" value="<?= limpar($trem['codigo']) ?>" required>
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control mb-3" value="<?= limpar($trem['nome']) ?>" required>
                                <label class="form-label">Linha</label>
                                <input type="text" name="linha" class="form-control mb-3" value="<?= limpar($trem['linha']) ?>" required>
                                <label class="form-label">Destino</label>
                                <input type="text" name="destino" class="form-control mb-3" value="<?= limpar($trem['destino']) ?>" required>
                                <label class="form-label">Rota relacionada</label>
                                <select name="id_rota" class="form-select mb-3" required>
                                    <?php foreach ($rotasDisponiveis as $rota): ?>
                                        <option value="<?= (int) $rota['id_rota'] ?>"
                                            <?= (int) $trem['id_rota'] === (int) $rota['id_rota'] ? 'selected' : '' ?>>
                                            <?= limpar($rota['codigo_rota'] . ' - ' . $rota['nome_rota']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label class="form-label">Velocidade</label>
                                <input type="number" min="0" name="velocidade" class="form-control mb-3" value="<?= (int) $trem['velocidade'] ?>" required>
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <?php foreach ($statusNomes as $valor => $nomeStatus): ?>
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
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>