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

    <title>Trens - MOVIX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <?php include '../components/sidebar.php'; ?>

    <div class="main">
        <div class="topbar">
            <h3>Gerenciamento de Trens</h3>
        </div>
        <div class="content">
            <div class="table-container">
                <h4 class="mb-3">Cadastrar Trens</h4>

                <div class="form-container mb-4">
                    <form method="POST">
                        <input type="hidden" name="cadastro_usuario" value="1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Código</label>
                                <input type="text" id="nome" name="nome" class="form-control"
                                    placeholder="Digite o Código" required>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario" class="form-label">Nome</label>
                                <input type="text" id="usuario" name="usuario" class="form-control"
                                    placeholder="Digite o Nome" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="cpf" class="form-label">Linha</label>
                                <input type="text" id="cpf" name="cpf" class="form-control" placeholder="Ex: Norte, Sul, Leste, Oeste" required>
                            </div>
                            <div class="col-md-4">
                                <label for="telefone" class="form-label">Destino</label>
                                <input type="tel" id="telefone" name="telefone" class="form-control"
                                    placeholder="Ex: São Paulo, Rio de Janeiro, Brasília" required>
                            </div>
                            <div class="col-md-4">
                                <label for="id_perfil" class="form-label">Status</label>
                                <select id="id_perfil" name="id_perfil" class="form-control">
                                    <option value="3">Operando</option>
                                    <option value="2">Manutenção</option>
                                    <option value="1">Fora de serviço</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="senha" class="form-label">Velocidade</label>
                                <input type="password" id="senha" name="senha" class="form-control"
                                    placeholder="Digite a velocidade" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Salvar usuário</button>
                        </div>
                    </form>
                </div>
        <div class="content">
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card sensor-card">
                        <div class="card-body">
                            <h6>Em operação</h6>
                            <h2>18</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card trem-card">
                        <div class="card-body">
                            <h6>Em Manutenção</h6>
                            <h2>04</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card alerta-card">
                        <div class="card-body">
                            <h6>Fora de serviço</h6>
                            <h2>02</h2>
                        </div>
                    </div>
                </div>

            </div>
            <div class="table-container">
                <h4>Frota de Trens</h4>
                <?php if ($podeGerenciar): ?>
                <button type="button" class="btn btn-sm btn-outline-primary">Novo trem</button>
                <?php endif; ?>
                <input type="text" placeholder="Pesquisar trem...">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Linha</th>
                            <th>Destino</th>
                            <th>Velocidade</th>
                            <th>Status</th>
                            <?php if ($podeGerenciar): ?>
                            <th>Ações</th>
                            <?php endif; ?>
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
                                <?php if ($podeGerenciar): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary">Editar</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary">Excluir</button>
                                <?php endif; ?>
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
                                <?php if ($podeGerenciar): ?>
                                <button type="button" class="btn btn-sm btn-outline-primary">Editar</button>
                                <button type="button" class="btn btn-sm btn-outline-primary">Excluir</button>
                                <?php endif; ?>
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