<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../infra/conexao.php';

$perfil = $_SESSION['id_perfil'] ?? 0;
$podeGerenciar = ($perfil == 1 || $perfil == 2);
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastro_sensor'])) {
    if (!$podeGerenciar) {
        $mensagem = 'Você não tem permissão para cadastrar sensores.';
    } else {
        $codigo = trim($_POST['codigo'] ?? '');
        $nome = trim($_POST['nome'] ?? '');
        $tipo = $_POST['tipo'] ?? '';
        $localizacao = trim($_POST['localizacao'] ?? '');
        $status = $_POST['status'] ?? '';

        if ($codigo === '' || $nome === '' || $localizacao === '') {
            $mensagem = 'Preencha todos os campos.';
        } else {
            $sql = "INSERT INTO sensores (codigo, nome, tipo, localizacao, status)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'sssss', $codigo, $nome, $tipo, $localizacao, $status);

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    header('Location: sensores.php');
                    exit;
                } else {
                    $mensagem = mysqli_stmt_errno($stmt) == 1062
                        ? 'Já existe um sensor com esse código.'
                        : 'Erro ao cadastrar o sensor.';
                }

                mysqli_stmt_close($stmt);
            } else {
                $mensagem = 'Erro ao preparar o cadastro.';
            }
        }
    }
}

$sql = "SELECT id, codigo, nome, tipo, localizacao, status FROM sensores ORDER BY id DESC";
$resultadoSensores = mysqli_query($conexao, $sql);

if (!$resultadoSensores) {
    die('Erro ao carregar os sensores.');
}
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

        <?php if ($mensagem !== ''): ?>
            <div class="content">
                <div class="alert alert-<?= $tipoMensagem ?>">
                    <?= htmlspecialchars($mensagem) ?>
                </div>
            </div>
        <?php endif; ?>



        <?php if ($podeGerenciar): ?>
            <div class="table-container">
                <h4 class="mb-3">Cadastrar Sensores</h4>

                <div class="form-container mb-4">
                    <form method="POST">
                        <input type="hidden" name="cadastro_sensor" value="1">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="codigo" class="form-label">ID</label>
                                <input type="text" id="codigo" name="codigo" class="form-control" placeholder="Ex: SN001"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" id="nome" name="nome" class="form-control"
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
                                <input type="text" id="localizacao" name="localizacao" class="form-control"
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
                        <?php while ($sensor = mysqli_fetch_assoc($resultadoSensores)): ?>
                            <tr>
                                <td><?= htmlspecialchars($sensor['codigo']) ?></td>
                                <td><?= htmlspecialchars($sensor['nome']) ?></td>
                                <td><?= htmlspecialchars($sensor['tipo']) ?></td>
                                <td><?= htmlspecialchars($sensor['localizacao']) ?></td>
                                <td>
                                    <span>
                                        <?= htmlspecialchars($sensor['status']) ?>
                                    </span>
                                </td>

                                <?php if ($podeGerenciar): ?>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" disabled>
                                            Editar
                                        </button>

                                        <a href="../funcoes/excluir_sensores.php?id=<?= (int) $sensor['id'] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem certeza que deseja excluir este sensor?');">
                                            Excluir
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>

                        <?php if (mysqli_num_rows($resultadoSensores) === 0): ?>
                            <tr>
                                <td colspan="<?= $podeGerenciar ? 6 : 5 ?>" class="text-center">
                                    Nenhum sensor cadastrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>