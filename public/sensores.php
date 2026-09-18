<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensores - MOVIX</title>
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
            <h3>Gerenciamento de Sensores</h3>
        </div>
    </div>
    <div class="content">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Sensores Cadastrados</h4>
                <button>
                    Novo Sensor
                </button>
            </div>
            <div class="mb-4">
                <input
                    type="text"
                    class="form-control">
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Localização</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SN001</td>
                        <td>Sensor Norte</td>
                        <td>Temperatura</td>
                        <td>KM 25</td>
                        <td>
                            <span >
                                Online
                            </span>
                        </td>
                        <td>
                            <button>
                                Editar
                            </button>
                            <button>
                                Excluir
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>SN002</td>
                        <td>Sensor Sul</td>
                        <td>Vibração</td>
                        <td>KM 48</td>
                        <td>
                            <span>
                                Atenção
                            </span>
                        </td>
                        <td>
                            <button>
                                Editar
                            </button>
                            <button>
                                Excluir
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>SN003</td>
                        <td>Sensor Centro</td>
                        <td>Pressão</td>
                        <td>KM 80</td>
                        <td>
                            <span>
                                Offline
                            </span>
                        </td>
                        <td>
                            <button>
                                Editar
                            </button>
                            <button>
                               Excluir
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>