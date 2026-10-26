<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../infra/conexao.php';

$perfil = $_SESSION['id_perfil'] ?? 0;
$podeGerenciar = ($perfil == 1 || $perfil == 2);

$sqlOnline = "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Online'";
$resultadoOnline = mysqli_query($conexao, $sqlOnline);
$sensoresOnline = mysqli_fetch_assoc($resultadoOnline)['total'];

$sqlOffline = "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Offline'";
$resultadoOffline = mysqli_query($conexao, $sqlOffline);
$sensoresOffline = mysqli_fetch_assoc($resultadoOffline)['total'];

$sqlAtencao = "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Atenção'";
$resultadoAtencao = mysqli_query($conexao, $sqlAtencao);
$sensoresAtencao = mysqli_fetch_assoc($resultadoAtencao)['total'];

$sqlSensores = "SELECT id, codigo, nome, localizacao, status FROM sensores ORDER BY id DESC";
$resultadoSensores = mysqli_query($conexao, $sqlSensores);

$sqlTrens = "SELECT codigo, nome, velocidade, status FROM trens ORDER BY id DESC";
$resultadoTrens = mysqli_query($conexao, $sqlTrens);

$trens = mysqli_fetch_all($resultadoTrens, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento - MOVIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php include '../components/sidebar.php'; ?>
    <div class="main">
        <div class="topbar">
            <div>
                <h3>Central de Monitoramento</h3>
            </div>
        </div>
        <div class="content">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card  online">
                        <div class="card-body">
                            <h5>Sensores Online</h5>
                            <h1><?= $sensoresOnline ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card warning">
                        <div class="card-body">
                            <h5> Em Atenção</h5>
                            <h1><?= $sensoresAtencao ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card offline">
                        <div class="card-body">
                            <h5>Offline</h5>
                            <h1><?= $sensoresOffline ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-7">
                    <div class="table-container">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">Sensores Monitorados</h4>

                            <a href="sensores.php" class="btn btn-sm btn-outline-primary">
                                Ver
                            </a>
                        </div>

                        <div class="col-lg-7">
                            <div class="table-container">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                </div>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Local</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php while ($sensor = mysqli_fetch_assoc($resultadoSensores)): ?>
                                            <tr>
                                                <td>
                                                    <?= htmlspecialchars($sensor['codigo'], ENT_QUOTES, 'UTF-8') ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($sensor['nome'], ENT_QUOTES, 'UTF-8') ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($sensor['localizacao'], ENT_QUOTES, 'UTF-8') ?>
                                                </td>

                                                <td>
                                                    <span>
                                                        <?= htmlspecialchars($sensor['status'], ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>

                                        <?php if (mysqli_num_rows($resultadoSensores) === 0): ?>
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    Nenhum sensor cadastrado.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="table-container">
                        <h4 class="mb-4">Trens em Operação</h4>

                        <?php if (count($trens) > 0): ?>
                            <?php foreach ($trens as $trem): ?>
                                <div class="status-item">
                                    <span>
                                        <?= htmlspecialchars($trem['nome'], ENT_QUOTES, 'UTF-8') ?>
                                        (<?= htmlspecialchars($trem['codigo'], ENT_QUOTES, 'UTF-8') ?>)
                                    </span>

                                    <strong>
                                        <?php if ((int) $trem['status'] === 3): ?>
                                            <?= (int) $trem['velocidade'] ?> km/h
                                        <?php elseif ((int) $trem['status'] === 2): ?>
                                            Manutenção
                                        <?php elseif ((int) $trem['status'] === 1): ?>
                                            Fora de serviço
                                        <?php else: ?>
                                            Desconhecido
                                        <?php endif; ?>
                                    </strong>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Nenhum trem cadastrado.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>