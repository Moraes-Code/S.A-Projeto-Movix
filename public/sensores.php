<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../infra/conexao.php';

$perfil = $_SESSION['id_perfil'] ?? 0;
$podeGerenciar = ($perfil == 1 || $perfil == 2);
$mensagem = '';
$tipoMensagem = 'danger';
$tipos = ['Temperatura', 'Vibração', 'Pressão'];
$statusDisponiveis = ['Online', 'Atenção', 'Offline'];

if (!isset($_SESSION['token_sensores'])) {
    $_SESSION['token_sensores'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_sensor'])) {
    if (
        !$podeGerenciar ||
        !isset($_POST['token']) ||
        !hash_equals($_SESSION['token_sensores'], $_POST['token'])
    ) {
        exit('Acesso não permitido.');
    }

    $id = (int) ($_POST['id_sensor'] ?? 0);
    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $localizacao = trim($_POST['localizacao'] ?? '');
    $status = $_POST['status'] ?? '';

    if ($id <= 0 || $codigo === '' || $nome === '' || $localizacao === '' ||
        !in_array($tipo, $tipos, true) || !in_array($status, $statusDisponiveis, true)) {
        $mensagem = 'Preencha os campos corretamente.';
    } else {
        try {
            $sql = $conexao->prepare(
                'UPDATE sensores SET codigo = ?, nome = ?, tipo = ?, localizacao = ?, status = ? WHERE id = ?'
            );
            $sql->bind_param('sssssi', $codigo, $nome, $tipo, $localizacao, $status, $id);
            $sql->execute();
            $sql->close();

            header('Location: sensores.php');
            exit;
        } catch (mysqli_sql_exception $erro) {
            $mensagem = $erro->getCode() == 1062
                ? 'Já existe um sensor com esse código.'
                : 'Erro ao atualizar o sensor.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastro_sensor'])) {
    if (!$podeGerenciar) {
        $mensagem = 'Você não tem permissão para cadastrar sensores.';
    } else {
        $codigo = trim($_POST['codigo'] ?? '');
        $nome = trim($_POST['nome'] ?? '');
        $tipo = $_POST['tipo'] ?? '';
        $localizacao = trim($_POST['localizacao'] ?? '');
        $status = $_POST['status'] ?? '';

        if ($codigo === '' || $nome === '' || $localizacao === '' ||
            !in_array($tipo, $tipos, true) || !in_array($status, $statusDisponiveis, true)) {
            $mensagem = 'Preencha os campos corretamente.';
        } else {
            try {
                $sql = $conexao->prepare(
                    'INSERT INTO sensores (codigo, nome, tipo, localizacao, status) VALUES (?, ?, ?, ?, ?)'
                );
                $sql->bind_param('sssss', $codigo, $nome, $tipo, $localizacao, $status);
                $sql->execute();
                $sql->close();

                header('Location: sensores.php');
                exit;
            } catch (mysqli_sql_exception $erro) {
                $mensagem = $erro->getCode() == 1062
                    ? 'Já existe um sensor com esse código.'
                    : 'Erro ao cadastrar o sensor.';
            }
        }
    }
}

$resultado = mysqli_query(
    $conexao,
    'SELECT id, codigo, nome, tipo, localizacao, status FROM sensores ORDER BY id DESC'
);

if (!$resultado) {
    die('Erro ao carregar os sensores.');
}

$sensores = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensores - MOVIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include '../components/sidebar.php'; ?>

    <div class="main">
        <div class="topbar">
            <div>
                <h3>Gerenciamento de Sensores</h3>
            </div>
        </div>

        <?php if ($mensagem !== ''): ?>
            <div class="content">
                <div class="alert alert-<?= $tipoMensagem ?>">
                    <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($podeGerenciar): ?>
            <div class="table-container">
                <h4 class="mb-3">Cadastrar Sensores</h4>
                <div class="form-container mb-4">
                    <form method="POST">
                        <input type="hidden" name="cadastro_sensor" value="1">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="codigo" class="form-label">ID</label>
                                <input type="text" id="codigo" name="codigo" class="form-control" placeholder="Ex: SN001" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome do sensor" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select id="tipo" name="tipo" class="form-control" required>
                                    <?php foreach ($tipos as $tipoOpcao): ?>
                                        <option value="<?= htmlspecialchars($tipoOpcao, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($tipoOpcao, ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="localizacao" class="form-label">Localização</label>
                                <input type="text" id="localizacao" name="localizacao" class="form-control" placeholder="Ex: KM 25" required>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-control" required>
                                    <?php foreach ($statusDisponiveis as $statusOpcao): ?>
                                        <option value="<?= htmlspecialchars($statusOpcao, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($statusOpcao, ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Salvar sensor</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="content">
            <div class="table-container">
                <div class="section-toolbar">
                    <h4>Sensores Cadastrados</h4>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Localização</th>
                            <th>Status</th>
                            <?php if ($podeGerenciar): ?><th>Ações</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sensores as $sensor): ?>
                            <tr>
                                <td><?= htmlspecialchars($sensor['codigo'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($sensor['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($sensor['tipo'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($sensor['localizacao'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><span><?= htmlspecialchars($sensor['status'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                <?php if ($podeGerenciar): ?>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#sensor<?= (int) $sensor['id'] ?>">Editar</button>
                                        <a href="../funcoes/excluir_sensores.php?id=<?= (int) $sensor['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este sensor?');">Excluir</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (count($sensores) === 0): ?>
                            <tr>
                                <td colspan="<?= $podeGerenciar ? 6 : 5 ?>" class="text-center">Nenhum sensor cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if ($podeGerenciar): ?>
        <?php foreach ($sensores as $sensorModal): ?>
            <div class="modal fade" id="sensor<?= (int) $sensorModal['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Sensor</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token_sensores'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="editar_sensor" value="1">
                                <input type="hidden" name="id_sensor" value="<?= (int) $sensorModal['id'] ?>">

                                <label class="form-label">Código</label>
                                <input type="text" name="codigo" class="form-control mb-3" value="<?= htmlspecialchars($sensorModal['codigo'], ENT_QUOTES, 'UTF-8') ?>" required>

                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control mb-3" value="<?= htmlspecialchars($sensorModal['nome'], ENT_QUOTES, 'UTF-8') ?>" required>

                                <label class="form-label">Tipo</label>
                                <select name="tipo" class="form-select mb-3" required>
                                    <?php foreach ($tipos as $tipoOpcao): ?>
                                        <option value="<?= htmlspecialchars($tipoOpcao, ENT_QUOTES, 'UTF-8') ?>" <?= $sensorModal['tipo'] === $tipoOpcao ? 'selected' : '' ?>><?= htmlspecialchars($tipoOpcao, ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <label class="form-label">Localização</label>
                                <input type="text" name="localizacao" class="form-control mb-3" value="<?= htmlspecialchars($sensorModal['localizacao'], ENT_QUOTES, 'UTF-8') ?>" required>

                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <?php foreach ($statusDisponiveis as $statusOpcao): ?>
                                        <option value="<?= htmlspecialchars($statusOpcao, ENT_QUOTES, 'UTF-8') ?>" <?= $sensorModal['status'] === $statusOpcao ? 'selected' : '' ?>><?= htmlspecialchars($statusOpcao, ENT_QUOTES, 'UTF-8') ?></option>
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
