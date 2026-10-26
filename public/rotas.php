<?php
session_start();
require_once '../infra/conexao.php';
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}
$perfil = $_SESSION['id_perfil'] ?? 0;
$podeGerenciar = ($perfil == 1);
if (!isset($_SESSION['token_rotas'])) {
    $_SESSION['token_rotas'] = bin2hex(random_bytes(32));
}
function limpar($texto)
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !$podeGerenciar ||
        !isset($_POST['token']) ||
        !hash_equals($_SESSION['token_rotas'], $_POST['token'])
    ) {
        exit('Acesso não permitido.');
    }
    try {
        if (isset($_POST['excluir_rota'])) {
            $id = (int) $_POST['id_rota'];
            $sql = $conexao->prepare("DELETE FROM rotas WHERE id_rota = ?");
            $sql->bind_param('i', $id);
            $sql->execute();
            $sql->close();
        } else {
            $nome = trim($_POST['nome_rota'] ?? '');
            $codigo = trim($_POST['codigo_rota'] ?? '');
            $origem = trim($_POST['origem'] ?? '');
            $destino = trim($_POST['destino'] ?? '');
            $distancia = (float) str_replace(',', '.', $_POST['distancia'] ?? '0');
            $tempo = trim($_POST['tempo_estimado'] ?? '');
            $status = $_POST['status'] ?? '';
            if (
                $nome == '' || $codigo == '' || $origem == '' ||
                $destino == '' || $tempo == '' || $distancia <= 0
            ) {
                throw new Exception('Preencha todos os campos.');
            }
            if (!in_array($status, ['Ativa', 'Inativa', 'Em manutenção'])) {
                throw new Exception('Status inválido.');
            }
            if (isset($_POST['editar_rota'])) {
                $id = (int) $_POST['id_rota'];
                $sql = $conexao->prepare(
                    "UPDATE rotas SET nome_rota=?, codigo_rota=?, origem=?,
                    destino=?, distancia=?, tempo_estimado=?, status=?
                    WHERE id_rota=?"
                );
                $sql->bind_param(
                    'ssssdssi',
                    $nome,
                    $codigo,
                    $origem,
                    $destino,
                    $distancia,
                    $tempo,
                    $status,
                    $id
                );
                $sql->execute();
                $sql->close();
            } else {
                $sql = $conexao->prepare(
                    "INSERT INTO rotas
                    (nome_rota, codigo_rota, origem, destino,
                    distancia, tempo_estimado, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?)"
                );
                $sql->bind_param(
                    'ssssdss',
                    $nome,
                    $codigo,
                    $origem,
                    $destino,
                    $distancia,
                    $tempo,
                    $status
                );
                $sql->execute();
                $sql->close();
                $_SESSION['mensagem_rotas'] = 'Rota cadastrada com sucesso!';
            }
        }
        header('Location: rotas.php');
        exit;
    } catch (mysqli_sql_exception $erro) {
        $mensagem = ($erro->getCode() == 1062)
            ? 'Esse código de rota já existe.'
            : 'Erro ao salvar os dados.';
    } catch (Exception $erro) {
        $mensagem = $erro->getMessage();
    }
}
if (isset($_SESSION['mensagem_rotas'])) {
    $mensagem = $_SESSION['mensagem_rotas'];
    unset($_SESSION['mensagem_rotas']);
}
$pesquisa = trim($_GET['pesquisa'] ?? '');
if ($pesquisa != '') {
    $termo = "%$pesquisa%";
    $sql = $conexao->prepare(
        "SELECT * FROM rotas WHERE nome_rota LIKE ? OR codigo_rota LIKE ?
        OR origem LIKE ? OR destino LIKE ? ORDER BY id_rota DESC"
    );
    $sql->bind_param('ssss', $termo, $termo, $termo, $termo);
} else {
    $sql = $conexao->prepare("SELECT * FROM rotas ORDER BY id_rota DESC");
}
$sql->execute();
$rotas = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
$sql->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas - MOVIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>
    <?php include '../components/sidebar.php'; ?>
    <div class="main">
        <div class="topbar">
            <h3>Gerenciamento de Rotas</h3>
        </div>
        <?php if ($mensagem != ''): ?>
            <div class="alert alert-info alert-dismissible fade show mt-3">
                <?= limpar($mensagem) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <div class="content">
            <div class="table-container">
                <?php if ($podeGerenciar): ?>
                    <h4 class="mb-3">Cadastrar Rotas</h4>
                    <div class="form-container mb-4">
                        <form method="POST">
                            <input type="hidden" name="token" value="<?= limpar($_SESSION['token_rotas']) ?>">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nome da Rota</label>
                                    <input name="nome_rota" class="form-control" placeholder="Digite o nome da rota"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Código da Rota</label>
                                    <input name="codigo_rota" class="form-control" placeholder="Digite o código da rota"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Origem</label>
                                    <input name="origem" class="form-control" placeholder="Digite a estação de origem"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Destino</label>
                                    <input name="destino" class="form-control" placeholder="Digite a estação de destino"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Distância (km)</label>
                                    <input type="number" name="distancia" class="form-control" min="0.1" step="0.1"
                                        placeholder="Ex.: 150" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tempo Estimado</label>
                                    <input name="tempo_estimado" class="form-control" placeholder="Ex.: 2 horas" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Ativa">Ativa</option>
                                        <option value="Inativa">Inativa</option>
                                        <option value="Em manutenção">Em manutenção</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button class="btn btn-primary">Salvar rota</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
                <h4 class="mb-3">Rotas cadastradas</h4>
                <form method="GET" class="row mb-4">
                    <div class="col-md-8">
                        <input name="pesquisa" class="form-control" placeholder="Pesquisar rota"
                            value="<?= limpar($pesquisa) ?>">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary">Pesquisar</button>
                    </div>
                    <?php if ($pesquisa != ''): ?>
                        <div class="col-auto">
                            <a href="rotas.php" class="btn btn-secondary">Limpar</a>
                        </div>
                    <?php endif; ?>
                </form>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome da Rota</th>
                                <th>Código</th>
                                <th>Origem</th>
                                <th>Destino</th>
                                <th>Distância</th>
                                <th>Tempo Estimado</th>
                                <th>Status</th>
                                <?php if ($podeGerenciar): ?>
                                    <th>Ações</th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($rotas) > 0): ?>
                                <?php foreach ($rotas as $r): ?>
                                    <tr>
                                        <td><?= limpar($r['nome_rota']) ?></td>
                                        <td><?= limpar($r['codigo_rota']) ?></td>
                                        <td><?= limpar($r['origem']) ?></td>
                                        <td><?= limpar($r['destino']) ?></td>
                                        <td><?= number_format($r['distancia'], 1, ',', '.') ?> km</td>
                                        <td><?= limpar($r['tempo_estimado']) ?></td>
                                        <td><?= limpar($r['status']) ?></td>
                                        <?php if ($podeGerenciar): ?>
                                            <td class="text-nowrap">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#rota<?= $r['id_rota'] ?>">
                                                    Editar
                                                </button>

                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="token"
                                                        value="<?= limpar($_SESSION['token_rotas']) ?>">

                                                    <input type="hidden" name="id_rota" value="<?= (int) $r['id_rota'] ?>">

                                                    <button type="submit" name="excluir_rota" value="1"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Tem certeza que deseja excluir esta rota?');">
                                                        Excluir
                                                    </button>
                                                </form>

                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= $podeGerenciar ? 8 : 7 ?>" class="text-center">Nenhuma rota encontrada.
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
        <?php foreach ($rotas as $r): ?>
            <div class="modal fade" id="rota<?= $r['id_rota'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Rota</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="token" value="<?= limpar($_SESSION['token_rotas']) ?>">
                                <input type="hidden" name="editar_rota" value="1">
                                <input type="hidden" name="id_rota" value="<?= $r['id_rota'] ?>">
                                <label class="form-label">Nome da Rota</label>
                                <input name="nome_rota" class="form-control mb-3" value="<?= limpar($r['nome_rota']) ?>"
                                    required>
                                <label class="form-label">Código da Rota</label>
                                <input name="codigo_rota" class="form-control mb-3" value="<?= limpar($r['codigo_rota']) ?>"
                                    required>
                                <label class="form-label">Origem</label>
                                <input name="origem" class="form-control mb-3" value="<?= limpar($r['origem']) ?>" required>
                                <label class="form-label">Destino</label>
                                <input name="destino" class="form-control mb-3" value="<?= limpar($r['destino']) ?>" required>
                                <label class="form-label">Distância (km)</label>
                                <input type="number" name="distancia" class="form-control mb-3" min="0.1" step="0.1"
                                    value="<?= limpar($r['distancia']) ?>" required>
                                <label class="form-label">Tempo Estimado</label>
                                <input name="tempo_estimado" class="form-control mb-3"
                                    value="<?= limpar($r['tempo_estimado']) ?>" required>
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <?php foreach (['Ativa', 'Inativa', 'Em manutenção'] as $s): ?>
                                        <option value="<?= $s ?>" <?= $r['status'] == $s ? 'selected' : '' ?>>
                                            <?= $s ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button class="btn btn-primary">Salvar alterações</button>
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