<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas - MOVIX</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            MOVIX
        </a>
        <a href="dashboard.php" class="btn btn-light btn-sm">
            Voltar
        </a>
    </div>
</nav>
<div class="container mt-5">
    <div class="d-flex justify-content-between">
        <div>
            <h1>Rotas</h1>
            <p class="text-muted">
                Gerenciamento das rotas ferroviárias.
            </p>
        </div>
        <div>
            <a
                href="cadastro.php?tipo=rota"
                class="btn btn-primary"
            >
                Nova Rota
            </a>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Distância</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>RT001</td>
                        <td>Rota Norte</td>
                        <td>Joinville</td>
                        <td>Curitiba</td>
                        <td>120 km</td>
                        <td>
                            <span class="badge bg-success">
                                Ativa
                            </span>
                        </td>
                        <td>
                            <a
                                href="cadastro.php?tipo=rota&acao=editar"
                                class="btn btn-warning btn-sm"
                            >
                                Editar
                            </a>
                            <button
                                class="btn btn-danger btn-sm"
                                onclick="alert('Exclusão será implementada posteriormente.')"
                            >
                                Excluir
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>RT002</td>
                        <td>Rota Centro</td>
                        <td>Joinville</td>
                        <td>Blumenau</td>
                        <td>55 km</td>
                        <td>
                            <span class="badge bg-success">
                                Ativa
                            </span>
                        </td>
                        <td>
                            <a
                                href="cadastro.php?tipo=rota&acao=editar"
                                class="btn btn-warning btn-sm"
                            >
                                Editar
                            </a>
                            <button
                                class="btn btn-danger btn-sm"
                                onclick="alert('Exclusão será implementada posteriormente.')"
                            >
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