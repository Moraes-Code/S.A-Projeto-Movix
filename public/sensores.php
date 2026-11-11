
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

$editar = null;

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
            $mensagem = 'Sensor cadastrado com sucesso!';
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
            $mensagem = 'Sensor atualizado com sucesso!';
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
            $mensagem = 'Sensor excluído com sucesso!';
        } else {
            $mensagem = 'Erro ao excluir o sensor.';
            $tipoMensagem = 'danger';
        }

        mysqli_stmt_close($stmt);
    }
}

// BUSCAR SENSOR PARA EDIÇÃO
if (isset($_GET['editar']) && $podeGerenciar) {

    $idEditar = (int) $_GET['editar'];

    $sqlEditar = "SELECT * FROM sensores WHERE id = ?";
    $stmtEditar = mysqli_prepare($conexao, $sqlEditar);

    mysqli_stmt_bind_param($stmtEditar, "i", $idEditar);
    mysqli_stmt_execute($stmtEditar);

    $resultadoEditar = mysqli_stmt_get_result($stmtEditar);
    $editar = mysqli_fetch_assoc($resultadoEditar);

    mysqli_stmt_close($stmtEditar);
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

                <h4 class="mb-3">
                    <?= $editar ? 'Editar Sensor' : 'Cadastrar Sensores' ?>
                </h4>

                <div class="form-container mb-4">

                    <form method="POST" action="sensores.php<?= $mostrarTodos ? '?todos=1' : '' ?>">

                        <?php if ($editar): ?>
                            <input type="hidden" name="id" value="<?= (int) $editar['id'] ?>">
                        <?php endif; ?>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">ID</label>

                                <input
                                    type="text"
                                    name="codigo"
                                    class="form-control"
                                    placeholder="Ex: SN001"
                                    value="<?= htmlspecialchars($editar['codigo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nome</label>

                                <input
                                    type="text"
                                    name="nome"
                                    class="form-control"
                                    placeholder="Digite o nome do sensor"
                                    value="<?= htmlspecialchars($editar['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tipo</label>

                                <input
                                    type="text"
                                    name="tipo"
                                    class="form-control"
                                    placeholder="Ex: Temperatura"
                                    value="<?= htmlspecialchars($editar['tipo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Localização</label>

                                <input
                                    type="text"
                                    name="localizacao"
                                    class="form-control"
                                    placeholder="Ex: KM 25"
                                    value="<?= htmlspecialchars($editar['localizacao'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-control" required>

                                    <?php
                                    $statusAtual = $editar['status'] ?? 'Online';
                                    ?>

                                    <option value="Online" <?= $statusAtual === 'Online' ? 'selected' : '' ?>>
                                        Online
                                    </option>

                                    <option value="Atenção" <?= $statusAtual === 'Atenção' ? 'selected' : '' ?>>
                                        Atenção
                                    </option>

                                    <option value="Offline" <?= $statusAtual === 'Offline' ? 'selected' : '' ?>>
                                        Offline
                                    </option>

                                </select>
                            </div>

                        </div>

                        <div class="text-end mt-4">

                            <?php if ($editar): ?>

                                <button type="submit" name="atualizar_sensor" class="btn btn-primary">
                                    Salvar alterações
                                </button>

                                <a href="sensores.php<?= $mostrarTodos ? '?todos=1' : '' ?>" class="btn btn-secondary">
                                    Cancelar
                                </a>

                            <?php else: ?>

                                <button type="submit" name="salvar_sensor" class="btn btn-primary">
                                    Salvar sensor
                                </button>

                            <?php endif; ?>

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

                                                <a
                                                    href="sensores.php?editar=<?= (int) $sensor['id'] ?><?= $mostrarTodos ? '&todos=1' : '' ?>"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Editar
                                                </a>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
