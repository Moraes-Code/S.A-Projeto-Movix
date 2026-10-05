<?php

require_once 'protecao_admin.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Administrador - MOVIX</title>

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

            <li>
                <a href="dashboard_adm.php">
                    Dashboard Administrativo
                </a>
            </li>

            <li>
                <a href="dashboard_usuario.php">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="usuarios.php">
                    Usuários
                </a>
            </li>

            <li>
                <a href="monitoramento.php">
                    Monitoramento
                </a>
            </li>

            <li>
                <a href="alertas.php">
                    Alertas
                </a>
            </li>

            <li>
                <a href="sensores.php">
                    Sensores
                </a>
            </li>

            <li>
                <a href="trens.php">
                    Trens
                </a>
            </li>

            <li>
                <a href="relatorios.php">
                    Relatórios
                </a>
            </li>

            <li>
                <a href="rotas.php">
                    Rotas
                </a>
            </li>

            <div class="botao-sair">

                <a href="../index.php?logout=1">
                    Sair
                </a>

            </div>

        </ul>

    </div>


    <div class="main">

        <div class="topbar">

            <div>

                <h3>Dashboard Administrativo</h3>

                <p>
                    Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
                </p>

            </div>

        </div>

        <div class="content">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="card dashboard-card bg-primary p-4">

                        <h5>Usuários</h5>

                        <p>
                            Gerenciamento de usuários do sistema.
                        </p>

                        <a href="usuarios.php" class="btn btn-light">
                            Gerenciar usuários
                        </a>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card dashboard-card bg-success p-4">

                        <h5>Monitoramento</h5>

                        <p>
                            Acompanhar informações ferroviárias.
                        </p>

                        <a href="monitoramento.php" class="btn btn-light">
                            Acessar
                        </a>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card dashboard-card bg-dark p-4">

                        <h5>Relatórios</h5>

                        <p>
                            Visualizar os relatórios do sistema.
                        </p>

                        <a href="relatorios.php" class="btn btn-light">
                            Acessar
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>