<?php
require_once __DIR__ . '/../funcoes/protecao_admin.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrativo - MOVIX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>

    <div class="sidebar">

        <div class="logo-area">
            <img src="../assets/imgs/logo_movix_semfundoazul.png" alt="Logo MOVIX">
            <h3>MOVIX</h3>
            <small>Centro Ferroviário</small>
        </div>

        <ul>
            <li><a href="dashboard_adm.php">Dashboard</a></li>
            <li><a href="monitoramento.php">Monitoramento</a></li>
            <li><a href="alertas.php">Alertas</a></li>
            <li><a href="sensores.php">Sensores</a></li>
            <li><a href="trens.php">Trens</a></li>
            <li><a href="usuarios.php">Usuários</a></li>
            <li><a href="relatorios.php">Relatórios</a></li>
            <li><a href="rotas.php">Rotas</a></li>
        </ul>

        <div class="botao-sair">
            <a href="../index.php?logout=1">Sair</a>
        </div>

    </div>

    <div class="main">

        <div class="topbar">
            <div>
                <h3>Dashboard Administrativo</h3>

                <p class="mb-0">
                    Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Administrador') ?>
                </p>
            </div>
        </div>

        <div class="content">

            <div class="mb-4">
                <h4>Painel de controle</h4>

                <p class="text-secondary">
                    Acesse as principais áreas de gerenciamento do MOVIX.
                </p>
            </div>

            <div class="row g-4">

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Usuários</h5>
                        <p>Gerencie os usuários cadastrados no sistema.</p>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Monitoramento</h5>
                        <p>Acesse a área de acompanhamento ferroviário.</p>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Relatórios</h5>
                        <p>Consulte a central de relatórios do sistema.</p>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Trens</h5>
                        <p>Consulte e gerencie os trens cadastrados.</p>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Sensores</h5>
                        <p>Acesse o cadastro e o gerenciamento dos sensores.</p>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Rotas</h5>
                        <p>Acesse a área de gerenciamento das rotas ferroviárias.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>

</html>