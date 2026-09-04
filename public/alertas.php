<?php
session_start();

// Se não estiver logado, redireciona para o login
// (ajuste o caminho conforme a estrutura do seu projeto)
if (!isset($_SESSION['usuario'])) {
    // header('Location: login.php');
    // exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Alertas - MOVIX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/style/style.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="sidebar">

    <div class="logo-area">

        <img src="../assets/imgs/logo_movix_semfundoazul.png">

        <h3>MOVIX</h3>

    </div>

    <a href="dashboard.php">
        <i class="fa-solid fa-chart-line"></i>
        Dashboard
    </a>

    <a href="monitoramento.php">
        <i class="fa-solid fa-satellite-dish"></i>
        Monitoramento
    </a>

    <a href="alertas.php" class="active">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Alertas
    </a>

    <a href="sensores.php">
        <i class="fa-solid fa-microchip"></i>
        Sensores
    </a>

    <a href="trens.php">
        <i class="fa-solid fa-train"></i>
        Trens
    </a>

    <a href="usuarios.php">
        <i class="fa-solid fa-users"></i>
        Usuários
    </a>

    <a href="relatorios.php">
        <i class="fa-solid fa-file-lines"></i>
        Relatórios
    </a>

</div>

<div class="main">

    <div class="topbar">

        <div>

            <h3>Central de Alertas</h3>

            <small>Monitoramento de ocorrências do sistema</small>

        </div>

    </div>

    <div class="content">

        <?php
        // Exemplo de dados dinâmicos - substitua pela sua consulta ao banco
        $ocorrencias = [
            [
                'id' => 'AL001',
                'tipo' => 'Falha de Sensor',
                'local' => 'Linha Norte',
                'data' => '19/06/2026',
                'prioridade' => 'Crítica',
                'status' => 'Aberto',
            ],
            [
                'id' => 'AL002',
                'tipo' => 'Temperatura Elevada',
                'local' => 'Linha Sul',
                'data' => '19/06/2026',
                'prioridade' => 'Média',
                'status' => 'Aberto',
            ],
            [
                'id' => 'AL003',
                'tipo' => 'Perda de Comunicação',
                'local' => 'Linha Centro',
                'data' => '18/06/2026',
                'prioridade' => 'Baixa',
                'status' => 'Resolvido',
            ],
        ];

        // Contadores calculados a partir dos dados
        $totalCriticos = 0;
        $totalMedios = 0;
        $totalResolvidos = 0;

        foreach ($ocorrencias as $oc) {
            if ($oc['status'] === 'Resolvido') {
                $totalResolvidos++;
            } elseif ($oc['prioridade'] === 'Crítica') {
                $totalCriticos++;
            } elseif ($oc['prioridade'] === 'Média') {
                $totalMedios++;
            }
        }
        ?>

        <div class="row g-4 mb-4">

            <div class="col-md-4">

                <div class="card monitor-card offline">

                    <div class="card-body">

                        <h5>🔴 Críticos</h5>

                        <h1><?php echo str_pad($totalCriticos, 2, '0', STR_PAD_LEFT); ?></h1>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card monitor-card warning">

                    <div class="card-body">

                        <h5>🟡 Médios</h5>

                        <h1><?php echo str_pad($totalMedios, 2, '0', STR_PAD_LEFT); ?></h1>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card monitor-card online">

                    <div class="card-body">

                        <h5>🟢 Resolvidos</h5>

                        <h1><?php echo str_pad($totalResolvidos, 2, '0', STR_PAD_LEFT); ?></h1>

                    </div>

                </div>

            </div>

        </div>

        <div class="table-container">

            <h4 class="mb-4">Ocorrências Registradas</h4>

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Local</th>
                        <th>Data</th>
                        <th>Prioridade</th>
                        <th>Status</th>
                        <th>Ações</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($ocorrencias as $oc):

                        switch ($oc['prioridade']) {
                            case 'Crítica':
                                $prioridadeClass = 'bg-danger';
                                break;
                            case 'Média':
                                $prioridadeClass = 'bg-warning';
                                break;
                            case 'Baixa':
                                $prioridadeClass = 'bg-info';
                                break;
                            default:
                                $prioridadeClass = 'bg-secondary';
                        }

                        $statusClass = $oc['status'] === 'Resolvido' ? 'bg-success' : 'bg-warning';
                    ?>

                    <tr>

                        <td><?php echo htmlspecialchars($oc['id']); ?></td>
                        <td><?php echo htmlspecialchars($oc['tipo']); ?></td>
                        <td><?php echo htmlspecialchars($oc['local']); ?></td>
                        <td><?php echo htmlspecialchars($oc['data']); ?></td>

                        <td>
                            <span class="badge <?php echo $prioridadeClass; ?>">
                                <?php echo htmlspecialchars($oc['prioridade']); ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($oc['status']); ?>
                            </span>
                        </td>

                        <td>

                            <?php if ($oc['status'] !== 'Resolvido'): ?>

                                <form action="processa_alerta.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($oc['id']); ?>">
                                    <input type="hidden" name="acao" value="resolver">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Resolver
                                    </button>
                                </form>

                                <a href="ver_alerta.php?id=<?php echo urlencode($oc['id']); ?>" class="btn btn-primary btn-sm">
                                    Ver
                                </a>

                            <?php else: ?>

                                <a href="historico_alerta.php?id=<?php echo urlencode($oc['id']); ?>" class="btn btn-secondary btn-sm">
                                    Histórico
                                </a>

                            <?php endif; ?>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>