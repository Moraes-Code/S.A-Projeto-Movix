
<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../infra/conexao.php';

$perfil = (int) ($_SESSION['id_perfil'] ?? 3);
$podeGerenciar = ($perfil === 1 || $perfil === 2);

$mensagem = '';
$tipoMensagem = 'success';

$mostrarTodos = isset($_GET['todos']) && $_GET['todos'] == '1';

// CADASTRAR SENSOR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_sensor'])) {

    if (!$podeGerenciar) {
        die('Você não tem permissão para realizar esta ação.');
    }

    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');
    $localizacao = trim($_POST['localizacao'] ?? '');
    $status = trim($_POST['status'] ?? 'Online');

    $statusPermitidos = ['Online', 'Offline', 'Atenção'];

    if (
        $codigo === '' || $nome === '' ||
        $tipo === '' || $localizacao === '' ||
        !in_array($status, $statusPermitidos, true)
    ) {
        $mensagem = 'Preencha todos os campos corretamente.';
        $tipoMensagem = 'danger';

    } else {

        $sql = "INSERT INTO sensores
                (codigo, nome, tipo, localizacao, status)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conexao, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $codigo,
            $nome,
            $tipo,
            $localizacao,
            $status
        );

        if (mysqli_stmt_execute($stmt)) {
            header('Location: sensores.php');
            exit;
        } else {
            $mensagem = 'Erro ao cadastrar. Verifique se o ID já existe.';
            $tipoMensagem = 'danger';
        }

        mysqli_stmt_close($stmt);
    }
}

// EDITAR SENSOR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar_sensor'])) {

    if (!$podeGerenciar) {
        die('Você não tem permissão para realizar esta ação.');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');
    $localizacao = trim($_POST['localizacao'] ?? '');
    $status = trim($_POST['status'] ?? '');

    $statusPermitidos = ['Online', 'Offline', 'Atenção'];

    if (
        $id <= 0 || $codigo === '' || $nome === '' ||
        $tipo === '' || $localizacao === '' ||
        !in_array($status, $statusPermitidos, true)
    ) {
        $mensagem = 'Preencha todos os campos corretamente.';
        $tipoMensagem = 'danger';

    } else {

        $sql = "UPDATE sensores
                SET codigo = ?, nome = ?, tipo = ?,
                    localizacao = ?, status = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($conexao, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssssi",
            $codigo,
            $nome,
            $tipo,
            $localizacao,
            $status,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            header('Location: sensores.php' . ($mostrarTodos ? '?todos=1' : ''));
            exit;
        } else {
            $mensagem = 'Erro ao atualizar o sensor. Verifique o ID.';
            $tipoMensagem = 'danger';
        }

        mysqli_stmt_close($stmt);
    }
}

// EXCLUIR SENSOR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_sensor'])) {

    if (!$podeGerenciar) {
        die('Você não tem permissão para realizar esta ação.');
    }

    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {

        $sql = "DELETE FROM sensores WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $sql);

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: sensores.php' . ($mostrarTodos ? '?todos=1' : ''));
            exit;
        } else {
            $mensagem = 'Erro ao excluir o sensor.';
            $tipoMensagem = 'danger';
        }

        mysqli_stmt_close($stmt);
    }
}

// CONTAR SENSORES
$sqlTotal = "SELECT COUNT(*) AS total FROM sensores";
$resultadoTotal = mysqli_query($conexao, $sqlTotal);
$totalSensores = mysqli_fetch_assoc($resultadoTotal)['total'];

// LISTAR SENSORES
$sqlSensores = "SELECT * FROM sensores ORDER BY id DESC";

if (!$mostrarTodos) {
    $sqlSensores .= " LIMIT 10";
}

$resultadoSensores = mysqli_query($conexao, $sqlSensores);

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
    <?php include '../components/notificacao.php'; ?>

    <div class="main">

        <div class="topbar">
            <div>
                <h3>Gerenciamento de Sensores</h3>
            </div>
        </div>

        <div class="content">

            <?php if ($mensagem !== ''): ?>
                <div class="alert alert-<?= $tipoMensagem ?> alert-dismissible fade show">
                    <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($podeGerenciar): ?>

                <h4 class="mb-3">Cadastrar Sensores</h4>

                <div class="form-container mb-4">

                    <form method="POST" action="sensores.php">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">ID</label>

                                <input
                                    type="text"
                                    name="codigo"
                                    class="form-control"
                                    placeholder="Ex: SN001"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nome</label>

                                <input
                                    type="text"
                                    name="nome"
                                    class="form-control"
                                    placeholder="Digite o nome do sensor"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tipo</label>

                                <select name="tipo" class="form-control" required>
                                    <option value="Temperatura">Temperatura</option>
                                    <option value="Vibração">Vibração</option>
                                    <option value="Pressão">Pressão</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Localização</label>

                                <input
                                    type="text"
                                    name="localizacao"
                                    class="form-control"
                                    placeholder="Ex: KM 25"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-control" required>
                                    <option value="Online">Online</option>
                                    <option value="Atenção">Atenção</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>

                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" name="salvar_sensor" class="btn btn-primary">
                                Salvar sensor
                            </button>
                        </div>

                    </form>

                </div>

            <?php endif; ?>

            <div class="table-container">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h4 class="mb-0">Sensores Cadastrados</h4>

                    <span class="text-muted">
                        Total: <?= (int) $totalSensores ?>
                    </span>

                </div>

                <div class="table-responsive">

                    <table class="table">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Localização</th>
                                <th>Status</th>

                                <?php if ($podeGerenciar): ?>
                                    <th>Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if ($resultadoSensores && mysqli_num_rows($resultadoSensores) > 0): ?>

                                <?php while ($sensor = mysqli_fetch_assoc($resultadoSensores)): ?>

                                    <tr>

                                        <td>
                                            <?= htmlspecialchars($sensor['codigo'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($sensor['nome'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($sensor['tipo'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($sensor['localizacao'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($sensor['status'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>

                                        <?php if ($podeGerenciar): ?>

                                            <td class="text-nowrap">

                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalSensor"
                                                    data-id="<?= (int) $sensor['id'] ?>"
                                                    data-codigo="<?= htmlspecialchars($sensor['codigo'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-nome="<?= htmlspecialchars($sensor['nome'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-tipo="<?= htmlspecialchars($sensor['tipo'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-localizacao="<?= htmlspecialchars($sensor['localizacao'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-status="<?= htmlspecialchars($sensor['status'], ENT_QUOTES, 'UTF-8') ?>">
                                                    Editar
                                                </button>

                                                <form method="POST" action="sensores.php<?= $mostrarTodos ? '?todos=1' : '' ?>" class="d-inline"
                                                    onsubmit="return confirm('Deseja realmente excluir este sensor?')">

                                                    <input
                                                        type="hidden"
                                                        name="id"
                                                        value="<?= (int) $sensor['id'] ?>">

                                                    <button
                                                        type="submit"
                                                        name="excluir_sensor"
                                                        class="btn btn-sm btn-danger">
                                                        Excluir
                                                    </button>

                                                </form>

                                            </td>

                                        <?php endif; ?>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="<?= $podeGerenciar ? 6 : 5 ?>" class="text-center">
                                        Nenhum sensor cadastrado.
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

                <?php if ($totalSensores > 10): ?>

                    <div class="text-center mt-3">

                        <?php if (!$mostrarTodos): ?>

                            <a href="sensores.php?todos=1" class="btn btn-outline-primary">
                                Ver mais
                            </a>

                        <?php else: ?>

                            <a href="sensores.php" class="btn btn-outline-primary">
                                Ver menos
                            </a>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

    <?php if ($podeGerenciar): ?>

        <div class="modal fade" id="modalSensor" tabindex="-1" aria-labelledby="modalSensorLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalSensorLabel">Editar Sensor</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">

                        <form action="sensores.php<?= $mostrarTodos ? '?todos=1' : '' ?>" method="POST">

                            <input type="hidden" id="modal-id" name="id">
                            <input type="hidden" name="atualizar_sensor" value="1">

                            <div class="mb-3">
                                <label for="modal-codigo" class="form-label">ID</label>
                                <input type="text" class="form-control" id="modal-codigo" name="codigo" required>
                            </div>

                            <div class="mb-3">
                                <label for="modal-nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="modal-nome" name="nome" required>
                            </div>

                            <div class="mb-3">
                                <label for="modal-tipo" class="form-label">Tipo</label>
                                <select class="form-control" id="modal-tipo" name="tipo" required>
                                    <option value="Temperatura">Temperatura</option>
                                    <option value="Vibração">Vibração</option>
                                    <option value="Pressão">Pressão</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="modal-localizacao" class="form-label">Localização</label>
                                <input type="text" class="form-control" id="modal-localizacao" name="localizacao" required>
                            </div>

                            <div class="mb-3">
                                <label for="modal-status" class="form-label">Status</label>
                                <select class="form-control" id="modal-status" name="status" required>
                                    <option value="Online">Online</option>
                                    <option value="Atenção">Atenção</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>

                            <div class="modal-footer px-0 pb-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>

                                <button type="submit" class="btn btn-primary">
                                    Atualizar
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>

        </div>

    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('modalSensor');

            if (!modal) return;

            modal.addEventListener('show.bs.modal', function (event) {
                const botao = event.relatedTarget;

                document.getElementById('modal-id').value = botao.dataset.id;
                document.getElementById('modal-codigo').value = botao.dataset.codigo;
                document.getElementById('modal-nome').value = botao.dataset.nome;
                document.getElementById('modal-tipo').value = botao.dataset.tipo;
                document.getElementById('modal-localizacao').value = botao.dataset.localizacao;
                document.getElementById('modal-status').value = botao.dataset.status;
            });
        });
    </script>

</body>
</html>
