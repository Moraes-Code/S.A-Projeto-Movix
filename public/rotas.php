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
    <div class="d-flex justify-content-between align-items-center">
    <div>

    <h1>Rotas</h1>

     <p class="text-muted">
         Gerenciamento das rotas ferroviárias.
    </p>
    </div>
    <a
         href="cadastro.php?tipo=rota"
        class="btn btn-primary"
     >
        Nova Rota
    </a>

    </div>


    <div class="row mt-4">

    <div class="col-md-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted">
                    Total de rotas
                </h6>

                <h3>
                    02
                </h3>

            </div>

         </div>

    </div>


    <div class="col-md-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                 <h6 class="text-muted">
                        Rotas ativas
                </h6>

                 <h3 class="text-success">
                        02
                </h3>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted">
                       Distância total
                </h6>

                <h3>
                    175 km
                 </h3>

            </div>

        </div>

    </div>

    </div>



    <div class="card mt-4">

    <div class="card-body">

          <label class="form-label">
                Pesquisar rota
          </label>
         <input
                type="text"
                class="form-control"
                placeholder="Digite o nome, código ou cidade..."
            >

      </div>

    </div>


    <div class="card mt-4 mb-5">

     <div class="card-body">

         <h4>
                Informações
         </h4>

         <p>
                Atualmente o sistema possui
                <strong>2 rotas cadastradas</strong>,
                todas em funcionamento.
          </p>

          <p class="mb-0">
                A distância total das rotas cadastradas é de
                <strong>175 km</strong>.
         </p>

</div>

    </div>

</div>


</body>

</html>