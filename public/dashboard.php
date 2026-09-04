<?php
session_start();

// Se não estiver logado, redireciona para o login
// (ajuste o caminho conforme a estrutura do seu projeto)
if (!isset($_SESSION['usuario'])) {
    // header('Location: login.php');
    // exit;
}

$nomeUsuario = $_SESSION['usuario']['nome'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MOVIX</title>
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

            <small>Centro Ferroviário</small>

        </div>

        <a href="dashboard.php" class="active">
            <i class="fa-solid fa-chart-line"></i>
            Dashboard
        </a>

        <a href="monitoramento.php">
            <i class="fa-solid fa-satellite-dish"></i>
            Monitoramento
        </a>

        <a href="alertas.php">
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

                <h3>Dashboard Geral</h3>

                <small>Visão geral do sistema ferroviário</small>

            </div>

            <div>

                <strong><?php echo htmlspecialchars($nomeUsuario); ?></strong>

            </div>

        </div>

        <div class="content">

            <div class="row g-4">

                <div class="col-lg-3">

                    <div class="card dashboard-card sensor-card">

                        <div class="card-body">

                            <i class="fa-solid fa-microchip dashboard-icon"></i>

                            <h6>Sensores Online</h6>

                            <h2>145</h2>

                            <small>98% Operacionais</small>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="card dashboard-card trem-card">

                        <div class="card-body">

                            <i class="fa-solid fa-train dashboard-icon"></i>

                            <h6>Trens Ativos</h6>

                            <h2>18</h2>

                            <small>Todos em operação</small>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="card dashboard-card alerta-card">

                        <div class="card-body">

                            <i class="fa-solid fa-triangle-exclamation dashboard-icon"></i>

                            <h6>Alertas</h6>

                            <h2>07</h2>

                            <small>2 críticos</small>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="card dashboard-card usuario-card">

                        <div class="card-body">

                            <i class="fa-solid fa-users dashboard-icon"></i>

                            <h6>Usuários</h6>

                            <h2>12</h2>

                            <small>Ativos</small>

                        </div>

                    </div>

                </div>

            </div>

            <div class="row mt-4">

                <div class="col-lg-8">

                    <div class="table-container">

                        <h4 class="mb-4">

                            Últimos Alertas

                        </h4>

                        <table class="table table-hover">

                            <thead>

                                <tr>

                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Local</th>
                                    <th>Data</th>
                                    <th>Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                // Exemplo de dados dinâmicos - substitua pela sua consulta ao banco
                                $alertas = [
                                    ['id' => 'AL001', 'tipo' => 'Falha Sensor', 'local' => 'Linha Norte', 'data' => '19/06/2026', 'status' => 'Crítico'],
                                    ['id' => 'AL002', 'tipo' => 'Temperatura', 'local' => 'Linha Sul', 'data' => '19/06/2026', 'status' => 'Médio'],
                                    ['id' => 'AL003', 'tipo' => 'Comunicação', 'local' => 'Linha Centro', 'data' => '19/06/2026', 'status' => 'Resolvido'],
                                ];

                                foreach ($alertas as $alerta):
                                    switch ($alerta['status']) {
                                        case 'Crítico':
                                            $badgeClass = 'bg-danger';
                                            break;
                                        case 'Médio':
                                            $badgeClass = 'bg-warning';
                                            break;
                                        case 'Resolvido':
                                            $badgeClass = 'bg-success';
                                            break;
                                        default:
                                            $badgeClass = 'bg-secondary';
                                    }
                                ?>

                                <tr>

                                    <td><?php echo htmlspecialchars($alerta['id']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['tipo']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['local']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['data']); ?></td>

                                    <td>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo htmlspecialchars($alerta['status']); ?>
                                        </span>
                                    </td>

                                </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="table-container">

                        <h4 class="mb-4">

                            Status da Rede

                        </h4>

                        <?php
                        $linhas = [
                            ['nome' => 'Linha Norte', 'status' => 'Operando', 'cor' => '🟢'],
                            ['nome' => 'Linha Sul', 'status' => 'Operando', 'cor' => '🟢'],
                            ['nome' => 'Linha Centro', 'status' => 'Atenção', 'cor' => '🟡'],
                            ['nome' => 'Linha Leste', 'status' => 'Falha', 'cor' => '🔴'],
                        ];

                        foreach ($linhas as $linha):
                        ?>

                        <div class="status-item">

                            <span><?php echo $linha['cor']; ?> <?php echo htmlspecialchars($linha['nome']); ?></span>

                            <strong><?php echo htmlspecialchars($linha['status']); ?></strong>

                        </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

            <div class="row mt-4">

                <div class="col-lg-6">

                    <div class="table-container">

                        <h4>Atividades Recentes</h4>

                        <ul class="list-group list-group-flush">

                            <?php
                            $atividades = [
                                'Sensor SN001 atualizado',
                                'Trem TR008 iniciou operação',
                                'Alerta AL003 resolvido',
                                'Novo usuário cadastrado',
                            ];

                            foreach ($atividades as $atividade):
                            ?>

                            <li class="list-group-item">
                                <?php echo htmlspecialchars($atividade); ?>
                            </li>

                            <?php endforeach; ?>

                        </ul>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="table-container">

                        <h4>Resumo Operacional</h4>

                        <p><strong>Malha Monitorada:</strong> 480 km</p>

                        <p><strong>Estações Ativas:</strong> 22</p>

                        <p><strong>Sensores Instalados:</strong> 145</p>

                        <p><strong>Disponibilidade:</strong> 98,7%</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>