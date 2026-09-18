<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trens - MOVIX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/style/style.css">
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
            <h3>Gerenciamento de Trens</h3>
        </div>
        <div class="content">
            <div class="row">
                <div class="col-md-4">
                    <div class="card  online">
                        <h5>Trens em Operação</h5>
                        <h1>18</h1>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card warning">
                        <h5>Em Manutenção</h5>
                        <h1>03</h1>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card offline">
                        <h5>Fora de Serviço</h5>
                        <h1>01</h1>
                    </div>
                </div>
            </div>
            <div class="table-container">
                <h4>Frota de Trens</h4>
                <button>
                    Novo Trem
                </button>
                <input
                    type="text"
                    placeholder="Pesquisar trem...">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Linha</th>
                            <th>Destino</th>
                            <th>Velocidade</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Trem 01</td>
                            <td>Trem Alpha</td>
                            <td>Norte</td>
                            <td>Joinville</td>
                            <td>80 km/h</td>
                            <td>
                                <span>
                                    Operando
                                </span>
                            </td>
                            <td>
                                <button >
                                    Editar
                                </button>

                                <button >
                                    Excluir
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Trem 02</td>
                            <td>Trem Beta</td>
                            <td>Sul</td>
                            <td>Curitiba</td>
                            <td>72 km/h</td>
                            <td>
                                <span>
                                    Manutenção
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
                            <td>Trem 03</td>
                            <td>Trem Gama</td>
                            <td>Centro</td>
                            <td>Blumenau</td>
                            <td>0 km/h</td>
                            <td>
                                <span>
                                    Parado
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