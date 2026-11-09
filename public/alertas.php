
<?php

session_start();

require_once __DIR__ . '/../infra/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

// Resolver alerta
if (isset($_POST['resolver_alerta'])) {

    $id = $_POST['id_alerta'];

    $sql = "UPDATE alertas SET status = 'Resolvido' WHERE id_alerta = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: alertas.php");
    exit;
}

// Filtros
$gravidade = $_GET['gravidade'] ?? '';
$status = $_GET['status'] ?? '';

$sql = "SELECT * FROM alertas WHERE 1=1";

if ($gravidade != '') {
    $sql .= " AND gravidade = '" . $conexao->real_escape_string($gravidade) . "'";
}

if ($status != '') {
    $sql .= " AND status = '" . $conexao->real_escape_string($status) . "'";
}

$sql .= " ORDER BY data_hora DESC";

$resultado = $conexao->query($sql);

// Indicadores
$resumo = $conexao->query("
    SELECT
        COUNT(*) AS total,
        SUM(gravidade = 'Critico') AS criticos,
        SUM(status = 'Pendente') AS pendentes,
        SUM(status = 'Resolvido') AS resolvidos
    FROM alertas
");

$dados = $resumo->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Alertas | MOVIX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>

    <?php include __DIR__ . '/../components/sidebar.php'; ?>

    <div class="main">

        <div class="topbar">
            <div>
                <h4>Central de Alertas</h4>
                <small class="text-muted">Monitoramento de ocorrências ferroviárias</small>
            </div>
        </div>

        <div class="content">

            <!-- Indicadores -->
            <div class="row g-4 mb-4">

                <div class="col-md-3">
                    <div class="card monitor-card online">
                        <div class="card-body">
                            <h6>Total de alertas</h6>
                            <h2><?= $dados['total'] ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card monitor-card offline">
                        <div class="card-body">
                            <h6>Críticos</h6>
                            <h2><?= $dados['criticos'] ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card monitor-card warning">
                        <div class="card-body">
                            <h6>Pendentes</h6>
                            <h2><?= $dados['pendentes'] ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card monitor-card online">
                        <div class="card-body">
                            <h6>Resolvidos</h6>
                            <h2><?= $dados['resolvidos'] ?></h2>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tabela de alertas -->
            <div class="table-container">

                <div class="section-toolbar">
                    <h4>Ocorrências Registradas</h4>
                </div>

                <!-- Filtros -->
                <form method="GET" class="row g-3 mb-4">

                    <div class="col-md-4">
                        <select name="gravidade" class="form-select">
                            <option value="">Todas as gravidades</option>
                            <option value="Critico" <?= $gravidade == 'Critico' ? 'selected' : '' ?>>Crítico</option>
                            <option value="Atencao" <?= $gravidade == 'Atencao' ? 'selected' : '' ?>>Atenção</option>
                            <option value="Informativo" <?= $gravidade == 'Informativo' ? 'selected' : '' ?>>Informativo</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">Todos os status</option>
                            <option value="Pendente" <?= $status == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="Resolvido" <?= $status == 'Resolvido' ? 'selected' : '' ?>>Resolvido</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-filter"></i> Filtrar
                        </button>

                        <a href="alertas.php" class="btn btn-secondary">Limpar</a>
                    </div>

                </form>

                <!-- Listagem -->
                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ocorrência</th>
                                <th>Equipamento</th>
                                <th>Data</th>
                                <th>Gravidade</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if ($resultado && $resultado->num_rows > 0): ?>

                                <?php while ($alerta = $resultado->fetch_assoc()): ?>

                                    <tr>
                                        <td><?= $alerta['id_alerta'] ?></td>

                                        <td>
                                            <strong><?= htmlspecialchars($alerta['titulo']) ?></strong>
                                            <details>
                                                <summary class="text-primary small">Ver descrição</summary>
                                                <small><?= htmlspecialchars($alerta['descricao']) ?></small>
                                            </details>
                                        </td>

                                        <td><?= htmlspecialchars($alerta['equipamento']) ?></td>

                                        <td><?= date('d/m/Y H:i', strtotime($alerta['data_hora'])) ?></td>

                                        <td>
                                            <?php if ($alerta['gravidade'] == 'Critico'): ?>
                                                <span class="badge bg-danger">Crítico</span>
                                            <?php elseif ($alerta['gravidade'] == 'Atencao'): ?>
                                                <span class="badge bg-warning text-dark">Atenção</span>
                                            <?php else: ?>
                                                <span class="badge bg-info text-dark">Informativo</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if ($alerta['status'] == 'Pendente'): ?>
                                                <span class="badge bg-warning text-dark">Pendente</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Resolvido</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if ($alerta['status'] == 'Pendente'): ?>
                                                <form method="POST">
                                                    <input type="hidden" name="id_alerta" value="<?= $alerta['id_alerta'] ?>">

                                                    <button type="submit" name="resolver_alerta" class="btn btn-sm btn-success">
                                                        Resolver
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-success">Concluído</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Nenhum alerta encontrado.
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</body>
</html>

