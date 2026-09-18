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
        <small>Centro Ferroviário</small>
    </div>
    <ul>
    <li>
        <a href="dashboard.php">Dashboard</a>
    </li>

    <li>
        <a href="monitoramento.php">Monitoramento</a>
    </li>

    <li>
        <a href="alertas.php">Alertas</a>
    </li>

    <li>
        <a href="sensores.php">Sensores</a>
    </li>

    <li>
        <a href="trens.php">Trens</a>
    </li>

    <li>
        <a href="usuarios.php">Usuários</a>
    </li>

    <li>
        <a href="relatorios.php">Relatórios</a>
    </li>

    <li>
        <a href="Rotas.php">Rotas</a>
    </li>
</ul>
</div>
<div class="main">
    <div class="topbar">
        <div>
            <h3>Central de Alertas</h3>
        </div>
    </div>
    <div class="content">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card offline">
                    <div class="card-body">
                        <h5>Críticos</h5>              
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card warning">
                    <div class="card-body">
                        <h5>Médios</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card online">
                    <div class="card-body">
                        <h5>Resolvidos</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-container">
            <h4 class="mb-4">Ocorrências Registradas</h4>
            <table class="table">
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
            </table>
        </div>
    </div>
</div>
</body>
</html>