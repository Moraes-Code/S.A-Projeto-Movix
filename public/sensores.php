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
    <title>Sensores - MOVIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php include '../components/navbar.php'; ?>
    
    <div class="main">
        <div class="topbar">
            <div>
                <h3>Gerenciamento de Sensores</h3>
            </div>
        </div>
        <div class="content">
            <div class="table-container">
                <div class="section-toolbar">
                    <h4>Sensores Cadastrados</h4>
                    <?php if ($podeGerenciar): ?>
                    <button type="button" class="btn btn-sm btn-outline-primary">
                        Novo Sensor
                    </button>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control">
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Localização</th>
                            <th>Status</th>
                            <?php if ($podeGerenciar): ?>
                            <th>Ações</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SN001</td>
                            <td>Sensor Norte</td>
                            <td>Temperatura</td>
                            <td>KM 25</td>
                            <td>
                                <span>
                                    Online
                                </span>
                            </td>
                            <td>
                                <?php if ($podeGerenciar): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary">Editar</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary">Excluir</button>
                                <?php endif; ?>
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
                                <?php if ($podeGerenciar): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary">Editar</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary">Excluir</button>
                                <?php endif; ?>
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
                                <?php if ($podeGerenciar): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary">Editar</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary">Excluir</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>