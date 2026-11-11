
<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../infra/conexao.php';

$perfil = $_SESSION['id_perfil'] ?? 0;
$podeGerenciar = ($perfil == 1 || $perfil == 2);


// Quantidade de sensores online
$sqlOnline = "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Online'";
$resultadoOnline = mysqli_query($conexao, $sqlOnline);
$sensoresOnline = $resultadoOnline
    ? mysqli_fetch_assoc($resultadoOnline)['total']
    : 0;


// Quantidade de sensores em atenção
$sqlAtencao = "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Atenção'";
$resultadoAtencao = mysqli_query($conexao, $sqlAtencao);
$sensoresAtencao = $resultadoAtencao
    ? mysqli_fetch_assoc($resultadoAtencao)['total']
    : 0;


// Quantidade de sensores offline
$sqlOffline = "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Offline'";
$resultadoOffline = mysqli_query($conexao, $sqlOffline);
$sensoresOffline = $resultadoOffline
    ? mysqli_fetch_assoc($resultadoOffline)['total']
    : 0;


// Buscar sensores cadastrados
$sqlSensores = "SELECT id, codigo, nome, localizacao, status
                FROM sensores
                ORDER BY id DESC";

$resultadoSensores = mysqli_query($conexao, $sqlSensores);


// Contador de sensores
$contador = 0;
$totalSensores = $resultadoSensores
    ? mysqli_num_rows($resultadoSensores)
    : 0;


// Buscar trens cadastrados
$sqlTrens = "SELECT codigo, nome, velocidade, status
             FROM trens
             ORDER BY id DESC";

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

            <!-- CLIMA -->
            <div class="row g-4 mb-4">

                <div class="col-12 col-md-6 col-xl-4">

                    <div class="card shadow-sm border-0 p-4 h-100" style="min-height: 130px;">

                        <h5 class="card-title">Clima em Joinville</h5>

                        <div class="d-flex align-items-center justify-content-between gap-3">

                            <div class="d-flex align-items-center gap-3">

                                <div>
                                    <h2 class="text-primary mb-0" id="clima-temperatura">
                                        -- °C
                                    </h2>
                                </div>

                                <div>
                                    <p class="fw-bold mb-1" id="clima-condicao">
                                        Carregando...
                                    </p>

                                    <small class="text-muted d-block" id="clima-local">
                                        Joinville, SC
                                    </small>

                                    <small class="text-muted d-block" id="clima-atualizacao"></small>
                                </div>

                            </div>

                            <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                id="btn-atualizar-clima">
                                Atualizar
                            </button>

                        </div>

                        <div class="alert alert-warning mt-3 mb-0 d-none" id="clima-erro">
                            Não foi possível carregar o clima. Tente novamente mais tarde.
                        </div>

                    </div>

                </div>

            </div>


            <!-- INDICADORES -->
            <div class="row g-4 mb-4">

                <div class="col-lg-4">
                    <div class="card shadow-sm p-3 h-100">
                        <h5>Sensores Online</h5>
                        <h1><?= $sensoresOnline ?></h1>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm p-3 h-100">
                        <h5>Em Atenção</h5>
                        <h1><?= $sensoresAtencao ?></h1>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm p-3 h-100">
                        <h5>Offline</h5>
                        <h1><?= $sensoresOffline ?></h1>
                    </div>
                </div>

            </div>


            <!-- SENSORES E TRENS -->
            <div class="row g-4">

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

                                    <?php if ($resultadoSensores && $totalSensores > 0): ?>

                                        <?php while ($sensor = mysqli_fetch_assoc($resultadoSensores)): ?>

                                            <tr class="<?= $contador >= 10 ? 'sensor-extra d-none' : '' ?>">

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
                                                    <?= htmlspecialchars($sensor['status'], ENT_QUOTES, 'UTF-8') ?>
                                                </td>

                                            </tr>

                                            <?php $contador++; ?>

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

                        <!-- BOTÃO VER MAIS -->
                        <?php if ($totalSensores > 10): ?>

                            <div class="text-center mt-3">

                                <button type="button"
                                    class="btn btn-outline-primary"
                                    id="verMaisSensores">

                                    Ver mais

                                </button>

                            </div>

                        <?php endif; ?>

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


    <!-- JAVASCRIPT DO CLIMA -->
    <script>

        function carregarClima() {

            const botao = document.getElementById('btn-atualizar-clima');

            botao.disabled = true;

            fetch('../funcoes/clima.php?t=' + new Date().getTime(), {
                cache: 'no-store'
            })

                .then(function (resposta) {

                    if (!resposta.ok) throw new Error('Erro');

                    return resposta.json();

                })

                .then(function (dados) {

                    if (!dados.ok) throw new Error('Erro');

                    document.getElementById('clima-temperatura').textContent =
                        Math.round(dados.temperatura) + ' °C';

                    document.getElementById('clima-condicao').textContent =
                        dados.condicao;

                    document.getElementById('clima-local').textContent =
                        dados.local;

                    document.getElementById('clima-atualizacao').textContent =
                        'Atualizado em ' + dados.atualizado_em;

                    document.getElementById('clima-erro').classList.add('d-none');

                })

                .catch(function () {

                    document.getElementById('clima-temperatura').textContent = '-- °C';

                    document.getElementById('clima-condicao').textContent =
                        'Clima indisponível';

                    document.getElementById('clima-erro').classList.remove('d-none');

                })

                .finally(function () {

                    botao.disabled = false;

                });

        }

        document.getElementById('btn-atualizar-clima')
            .addEventListener('click', carregarClima);

        carregarClima();

        setInterval(carregarClima, 10 * 60 * 1000);


        // VER MAIS SENSORES

        const botaoSensores = document.getElementById('verMaisSensores');

        if (botaoSensores) {

            botaoSensores.addEventListener('click', function () {

                const sensores = document.querySelectorAll('.sensor-extra');

                const escondidos = sensores[0].classList.contains('d-none');

                sensores.forEach(function (sensor) {

                    sensor.classList.toggle('d-none');

                });

                botaoSensores.textContent = escondidos
                    ? 'Ver menos'
                    : 'Ver mais';

            });

        }

    </script>

</body>

</html>
