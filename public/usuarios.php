<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Usuários - MOVIX</title>

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

            <h1>Usuários</h1>

            <p class="text-muted">Gerencie os usuários do sistema</p>

        </div>

        <div>

            <a
            href="cadastro.php?tipo=usuario"
            class="btn btn-movix"
            >
                <i class="fa-solid fa-user-plus"></i>
                Novo Usuário
            </a>

        </div>

    </div>

    <div class="card mt-4">

        <div class="card-body">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>Nome</th>

                        <th>Email</th>

                        <th>Tipo</th>

                        <th>Ações</th>

                    </tr>

                </thead>

                <tbody>

                <tr>

                    <td>João Silva</td>

                    <td>Joao@movix.com</td>

                    <td>Administrador</td>

                    <td>000.000.000-00</td>

                    <td>
                        <span class="badge bg-success">Ativo</span>
                    </td>
                    <td>

            
                <a 
                    href="cadastro.php?tipo=usuario&acao=editar"
                                class="btn btn-warning btn-sm"
                            >
                                Editar
                </a>

            
                <button 
                    class="btn btn-danger btn-sm"
                    onclick="alertqq('Exclusão será implementada posteriormente.')"

                    >
                        Excluir
                </button>

                    </td>

                    <tr>



                    </tr>


                    <td>Maria Santos</td>
                    <td>maria@movix.com</td>
                    <td>111.111.111-11</td>
                    <td>Operador</td>

                    <td>
                        <span class="badge bg-success">
                            Ativo
                        </span>

                    </td>

                    <td>


                    <a
                        href="cadastro.php?tipo=usuario&acao=editar"
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

            <tbody>

        <table>

    </div>

</div>

</body>

</html>
            