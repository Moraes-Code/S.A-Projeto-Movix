<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}
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
    <?php include '../components/sidebar.php'; ?>

    <div class="main">
        <div class="topbar">
            <div>
                <h3>Gerenciamento de Sensores</h3>
            </div>
        </div>


            
<?php if ($podeGerenciar): ?>
    <div class="table-container">
        <h4 class="mb-3">Cadastrar Sensores</h4>

        <div class="form-container mb-4">
            <form method="POST">
                <input type="hidden" name="cadastro_sensor" value="1">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">ID</label>
                        <input type="text" id="codigo" name="codigo"
                            class="form-control"
                            placeholder="Ex: SN001" required>
                    </div>

                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" id="nome" name="nome"
                            class="form-control"
                            placeholder="Digite o nome do sensor" required>
                    </div>

                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select id="tipo" name="tipo" class="form-control" required>
                            <option value="Temperatura">Temperatura</option>
                            <option value="Vibração">Vibração</option>
                            <option value="Pressão">Pressão</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="localizacao" class="form-label">Localização</label>
                        <input type="text" id="localizacao" name="localizacao"
                            class="form-control"
                            placeholder="Ex: KM 25" required>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="Online">Online</option>
                            <option value="Atenção">Atenção</option>
                            <option value="Offline">Offline</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        Salvar sensor
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>


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
                                    <a href="../funcoes/excluir_sensores.php?id=<?= (int) ($sensor['id'] ?? 0) ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza que deseja excluir o sensor <?= htmlspecialchars($sensor['nome'] ?? '', ENT_QUOTES) ?>?');">
                                        Excluir
                                    </a>
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
                                    <a href="../funcoes/excluir_sensores.php?id=<?= (int) ($sensor['id'] ?? 0) ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza que deseja excluir o sensor <?= htmlspecialchars($sensor['nome'] ?? '', ENT_QUOTES) ?>?');">
                                        Excluir
                                    </a>
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
                                    <a href="../funcoes/excluir_sensores.php?id=<?= (int) ($sensor['id'] ?? 0) ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza que deseja excluir o sensor <?= htmlspecialchars($sensor['nome'] ?? '', ENT_QUOTES) ?>?');">
                                        Excluir
                                    </a>
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