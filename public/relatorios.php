<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - MOVIX</title>
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
</div>
<div class="main">
    <div class="topbar">
        <div>
            <h3>Central de Relatórios</h3>
        </div>
    </div>
    <div class="content">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card sensor-card">
                    <div class="card-body">
                        <h6>Sensores</h6>
                        <h2>145</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card trem-card">
                    <div class="card-body">
                        <h6>Trens</h6>
                        <h2>18</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card alerta-card">
                    <div class="card-body">
                        <h6>Alertas</h6>
                        <h2>25</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card usuario-card">
                    <div class="card-body">
                        <h6>Usuários</h6>
                        <h2>12</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-container mb-4">
            <h4 >
                Filtros de Relatório
            </h4>
            <div class="row">
                <div class="col-md-3">
                    <label>Data Inicial</label>
                    <input type="date">
                </div>
                <div class="col-md-3">
                    <label>Data Final</label>
                    <input type="date">
                </div>
                <div class="col-md-3">
                    <label>Tipo</label>
                    <select >
                        <option>Sensores</option>
                        <option>Trens</option>
                        <option>Alertas</option>
                        <option>Usuários</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button>
                        Gerar Relatório
                    </button>
                </div>
            </div>
        </div>
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Relatórios Disponíveis</h4>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Relatório</th>
                        <th>Data</th>
                        <th>Responsável</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Relatório 01</td>
                        <td>Sensores</td>
                        <td>19/06/2026</td>
                        <td>Administrador</td>
                        <td>
                            <button>
                                PDF
                            </button>
                            <button >
                                Excel
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Relatório 02</td>
                        <td>Trens</td>
                        <td>24/08/2026</td>
                        <td>Supervisor</td>
                        <td>
                            <button >
                                PDF
                            </button>
                            <button>
                                Excel
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Relatório 03</td>
                        <td>Alertas</td>
                        <td>04/04/2026</td>
                        <td>Operador</td>
                        <td>
                            <button>
                                PDF
                            </button>
                            <button>
                                Excel
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