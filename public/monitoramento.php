
<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../infra/conexao.php';

$perfil = $_SESSION['id_perfil'] ?? 0;
$podeGerenciar = ($perfil == 1 || $perfil == 2);

// Contagem dos sensores
$sqlOnline = "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Online'";
$resultadoOnline = mysqli_query($conexao, $sqlOnline);
$sensoresOnline = $resultadoOnline ? mysqli_fetch_assoc($resultadoOnline)['total'] : 0;

$sqlOffline = "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Offline'";
$resultadoOffline = mysqli_query($conexao, $sqlOffline);
$sensoresOffline = $resultadoOffline ? mysqli_fetch_assoc($resultadoOffline)['total'] : 0;

$sqlAtencao = "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Atenção'";
$resultadoAtencao = mysqli_query($conexao, $sqlAtencao);
$sensoresAtencao = $resultadoAtencao ? mysqli_fetch_assoc($resultadoAtencao)['total'] : 0;

// Consulta dos sensores
$sqlSensores = "SELECT id, codigo, nome, localizacao, status FROM sensores ORDER BY id DESC";
$resultadoSensores = mysqli_query($conexao, $sqlSensores);

// Consulta dos trens
$sqlTrens = "SELECT codigo, nome, velocidade, status FROM trens ORDER BY id DESC";
$resultadoTrens = mysqli_query($conexao, $sqlTrens);

$trens = $resultadoTrens
    ? mysqli_fetch_all($resultadoTrens, MYSQLI_ASSOC)
    : [];

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

    <style>

        /* DIAGRAMA DE RELACIONAMENTO */

        .fluxo-ferroviario {
            background: #050505;
            border: 1px solid #303030;
            border-radius: 18px;
            padding: 16px;
            margin-bottom: 30px;
        }

        .fluxo-titulo {
            color: #FFFFFF;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .fluxo-item {
            min-height: 95px;
            border-radius: 15px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #111827;
        }

        .fluxo-item i {
            font-size: 20px;
            margin-bottom: 6px;
        }

        .fluxo-item h5 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .fluxo-item p {
            font-size: 14px;
            margin: 0;
        }

        .fluxo-usuario {
            background: #DCEAFE;
        }

        .fluxo-rota {
            background: #DDF5E5;
        }

        .fluxo-trem {
            background: #FCECCB;
        }

        .fluxo-sensor {
            background: #EBDDF8;
        }

        .fluxo-seta {
            color: #FFFFFF;
            text-align: center;
            padding: 9px;
            font-size: 20px;
        }

        @media (max-width: 576px) {

            .fluxo-ferroviario {
                padding: 10px;
            }

            .fluxo-item p {
                font-size: 12px;
            }

        }

    </style>

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

            <!-- DIAGRAMA FERROVIÁRIO -->

            <div class="fluxo-ferroviario">

                <h4 class="fluxo-titulo">
                    Relacionamento do Sistema Ferroviário
                </h4>

                <div class="fluxo-item fluxo-usuario">

                    <i class="fa-regular fa-user"></i>

                    <h5>1. USUÁRIO</h5>

                    <p>Possui ou gerencia uma rota</p>

                </div>

                <div class="fluxo-seta">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>

                <div class="fluxo-item fluxo-rota">

                    <i class="fa-solid fa-route"></i>

                    <h5>2. ROTA</h5>

                    <p>Utiliza um trem</p>

                </div>

                <div class="fluxo-seta">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>

                <div class="fluxo-item fluxo-trem">

                    <i class="fa-solid fa-train"></i>

                    <h5>3. TREM</h5>

                    <p>Possui ou utiliza sensores</p>

                </div>

                <div class="fluxo-seta">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>

                <div class="fluxo-item fluxo-sensor">

                    <i class="fa-solid fa-tower-broadcast"></i>

                    <h5>4. SENSOR</h5>

                    <p>Monitora velocidade, localização ou estado do trem</p>

                </div>

            </div>

            <!-- INDICADORES -->

            <div class="row g-4">

                <div class="col-lg-4">

                    <div class="card online">

                        <div class="card-body">

                            <h5>Sensores Online</h5>

                            <h1><?= $sensoresOnline ?></h1>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="card warning">

                        <div class="card-body">

                            <h5>Em Atenção</h5>

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

            <!-- TABELAS -->

            <div class="row mt-4">

                <!-- SENSORES -->

                <div class="col-lg-7">

                    <div class="table-container">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h4 class="mb-0">Sensores Monitorados</h4>

                            <a href="sensores.php" class="btn btn-sm btn-outline-primary">
                                Ver
                            </a>

                        </div>

                        <div class="table-responsive">

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
                                                    <?= htmlspecialchars($sensor['localizacao'], ENT_QUOTES, 'UTF-8') ?>
                                                </td>

                                                <td>
                                                    <span>
                                                        <?= htmlspecialchars($sensor['status'], ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                </td>

                                            </tr>

                                        <?php endwhile; ?>

                                    <?php else: ?>

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

                <!-- TRENS -->

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
