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
    <title>Alertas - MOVIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php include '../components/navbar.php'; ?>
    
    <div class="main">
        <div class="topbar">
            <div>
                <h3>Central de Alertas</h3>
            </div>
        </div>
        <div class="content">
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card offline">
                        <div class="card-body">
                            <h6>Críticos</h6>
                            <h4>02</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card warning">
                        <div class="card-body">
                            <h6>Médios</h6>
                            <h4>09</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card online">
                        <div class="card-body">
                            <h6>Resolvidos</h6>
                            <h4>21</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-container">
                <h4 class="mb-4">Ocorrências Registradas</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Local</th>
                            <th>Data</th>
                            <th>Prioridade</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>

</html>