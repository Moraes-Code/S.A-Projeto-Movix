<?php

session_start(); 

if (!isset($_SESSION['usuario_id'])) { header('Location: ../index.php'); 
exit; } 
$perfil = $_SESSION['id_perfil'];
$podeGerenciar = ($perfil == 1 || $perfil == 2);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - MOVIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php include '../components/sidebar.php'; ?>
    
    <div class="main">
        <div class="topbar">
            <div>
                <h3>Central de Relatórios</h3>
            </div>
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
                        <h3>
                            02
                        </h3>
                    </div>
                </div>
            </div>
            

            <<div class="content">
    <div class="table-container">
        <h4 class="mb-3">Cadastrar Rotas</h4>

        <div class="form-container mb-4">
            <form method="POST">
                <input type="hidden" name="cadastro_rota" value="1">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="nome_rota" class="form-label">Nome da Rota</label>
                        <input type="text" id="nome_rota" name="nome_rota"
                            class="form-control"
                            placeholder="Digite o nome da rota" required>
                    </div>

                    <div class="col-md-6">
                        <label for="codigo_rota" class="form-label">Código da Rota</label>
                        <input type="text" id="codigo_rota" name="codigo_rota"
                            class="form-control"
                            placeholder="Digite o código da rota" required>
                    </div>

                    <div class="col-md-6">
                        <label for="origem" class="form-label">Origem</label>
                        <input type="text" id="origem" name="origem"
                            class="form-control"
                            placeholder="Digite a estação de origem" required>
                    </div>

                    <div class="col-md-6">
                        <label for="destino" class="form-label">Destino</label>
                        <input type="text" id="destino" name="destino"
                            class="form-control"
                            placeholder="Digite a estação de destino" required>
                    </div>

                    <div class="col-md-4">
                        <label for="distancia" class="form-label">Distância (km)</label>
                        <input type="number" id="distancia" name="distancia"
                            class="form-control"
                            placeholder="Ex.: 150"
                            min="0.1" step="0.1" required>
                    </div>

                    <div class="col-md-4">
                        <label for="tempo_estimado" class="form-label">Tempo Estimado</label>
                        <input type="text" id="tempo_estimado" name="tempo_estimado"
                            class="form-control"
                            placeholder="Ex.: 2 horas" required>
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="Ativa">Ativa</option>
                            <option value="Inativa">Inativa</option>
                            <option value="Em manutenção">Em manutenção</option>
                        </select>
                    </div>

                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        Salvar rota
                    </button>
                </div>
            </form>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                <input type="text" class="form-control"
                    placeholder="Pesquisar rota">
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Nome da Rota</th>
                    <th>Código</th>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Distância</th>
                    <th>Tempo Estimado</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
               
            </tbody>
        </table>

    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>