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
        <a href="dashboard.php">
            Dashboard
        </a>
        <a href="monitoramento.php">
            Monitoramento
        </a>
        <a href="alertas.php">
            Alertas
        </a>
        <a href="sensores.php">
            Sensores
        </a>
        <a href="trens.php">
            Trens
        </a>
        <a href="usuarios.php">
            Usuários
        </a>
        <a href="relatorios.php">
            Relatórios
        </a>
        <a href="Rotas.php">
            Rotas
        </a>
        <div class="botao-sair">
    <a href="index.html">
        Sair
    </a>
</div>
    </div>
    <div class="main">
        <div class="topbar">
            <div>
                <h3>Dashboard Geral</h3>
                <small>Visão geral do sistema ferroviário</small>
            </div>
        </div>
        <div class="content">
            <div class="row g-4">
                <div class="col-lg-3">
                    <div class="card sensor-card">
                        <div class="card-body">
                            <h6>Sensores Online</h6>
                            <h2>145</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card trem-card">
                        <div class="card-body">
                            <h6>Trens Ativos</h6>
                            <h2>18</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card alerta-card">
                        <div class="card-body">
                            <h6>Alertas</h6>
                            <h2>07</h2>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card usuario-card">
                        <div class="card-body">
                            <h6>Usuários</h6>
                            <h2>12</h2>
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
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="table-container">
                        <h4 class="mb-4">
                            Status da Rede
                        </h4>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="table-container">
                        <h4>Atividades Recentes</h4>
                        <p><strong>Malha Monitorada:</strong></p>
                        <p><strong>Estações Ativas:</strong></p>
                        <p><strong>Sensores Instalados:</strong></p>
                        <p><strong>Disponibilidade:</strong></p>
                        <ul class="list-group list-group-flush">
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="table-container">
                        <h4>Resumo Operacional</h4>
                        <p><strong>Malha Monitorada:</strong></p>
                        <p><strong>Estações Ativas:</strong></p>
                        <p><strong>Sensores Instalados:</strong></p>
                        <p><strong>Disponibilidade:</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>