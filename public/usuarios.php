
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Usuários - MOVIX</title>
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
</div>
<div class="main">
<div class="topbar">
    <div>
        <h3>Gerenciamento de Usuários</h3>
    </div>
</div>
<div class="content">
    <div class="table-container">
        <div >
            <h4>Usuários Cadastrados</h4>
            <button>
                Novo Usuário
            </button>
        </div>
        <div class="row mb-4">
            <div class="col-md-8">
                <input
                    type="text"
                    placeholder="Pesquisar usuário">
            </div>
            <div class="col-md-4">
                <select >
                    <option>Todos os Perfis</option>
                    <option>Administrador</option>
                    <option>Supervisor</option>
                    <option>Operador</option>
                </select>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>André Silva</strong>
                    </td>
                    <td>
                        andre@movix.com
                    </td>
                    <td>
                        (47) 99999-9999
                    </td>
                    <td>
                        <span>
                            Administrador
                        </span>
                    </td>
                    <td>
                        <span>
                            Ativo
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
                    <td>
                        <strong>Maria Souza</strong>
                    </td>
                    <td>
                        maria@movix.com
                    </td>
                    <td>
                        (47) 98888-8888
                    </td>
                    <td>
                        <span>
                            Supervisor
                        </span>
                    </td>
                    <td>
                        <span>
                            Ativo
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
                    <td>
                        <strong>Carlos Lima</strong>
                    </td>
                    <td>
                        carlos@movix.com
                    </td>
                    <td>
                        (47) 97777-7777
                    </td>
                    <td>
                        <span>
                            Operador
                        </span>
                    </td>
                    <td>
                        <span>
                            Inativo
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
