
<?php
session_start();
require_once '../infra/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$mensagemSucesso = '';
$mensagemErro = '';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$perfil = (int) ($_SESSION['id_perfil'] ?? 3);
$podeGerenciar = ($perfil === 1);

// Atualizar trem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    if (!$podeGerenciar) {
        header('Location: trens.php');
        exit;
    }

    $id = (int) $_POST['id'];
    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $linha = trim($_POST['linha'] ?? '');
    $destino = trim($_POST['destino'] ?? '');
    $velocidade = (int) ($_POST['velocidade'] ?? 0);
    $status = (int) ($_POST['status'] ?? 3);

    $sql = "UPDATE trens
            SET codigo = ?, nome = ?, linha = ?, destino = ?,
                velocidade = ?, status = ?
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param(
        "ssssiii",
        $codigo,
        $nome,
        $linha,
        $destino,
        $velocidade,
        $status,
        $id
    );

    if ($stmt->execute()) {
        header('Location: trens.php');
        exit;
    } else {
        $mensagemErro = 'Erro ao atualizar trem. Verifique se o código já está sendo usado.';
    }
}

// Cadastro de trens
if ($podeGerenciar) {
    include("../funcoes/cadastro_trens.php");
}

// Buscar trens
$sql = 'SELECT * FROM trens ORDER BY id DESC';
$resultado = mysqli_query($conexao, $sql);
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

            <?php if ($mensagemErro): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($mensagemErro) ?>
                </div>
            <?php endif; ?>

            <?php if ($podeGerenciar): ?>
                <div class="table-container">
                    <h4 class="mb-3">Cadastrar Trens</h4>

                    <div class="form-container mb-4">
                        <form method="POST">
                            <input type="hidden" name="cadastro_trem" value="1">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" id="codigo" name="codigo"
                                        class="form-control" placeholder="Digite o Código" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="nome" class="form-label">Nome</label>
                                    <input type="text" id="nome" name="nome"
                                        class="form-control" placeholder="Digite o Nome" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="linha" class="form-label">Linha</label>
                                    <input type="text" id="linha" name="linha"
                                        class="form-control"
                                        placeholder="Ex: Norte, Sul, Leste, Oeste" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="destino" class="form-label">Destino</label>
                                    <input type="text" id="destino" name="destino"
                                        class="form-control"
                                        placeholder="Ex: São Paulo, Rio de Janeiro, Brasília" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="3">Operando</option>
                                        <option value="2">Manutenção</option>
                                        <option value="1">Fora de serviço</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="velocidade" class="form-label">Velocidade</label>
                                    <input type="number" id="velocidade" name="velocidade"
                                        class="form-control" placeholder="Digite a velocidade" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">Salvar trem</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card sensor-card">
                        <div class="card-body">
                            <h6>Operando</h6>
                            <h2>
                                <?php
                                $contagem = mysqli_query(
                                    $conexao,
                                    "SELECT COUNT(*) AS total FROM trens WHERE status = 3"
                                );
                                echo mysqli_fetch_assoc($contagem)['total'];
                                ?>
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card trem-card">
                        <div class="card-body">
                            <h6>Manutenção</h6>
                            <h2>
                                <?php
                                $contagem = mysqli_query(
                                    $conexao,
                                    "SELECT COUNT(*) AS total FROM trens WHERE status = 2"
                                );
                                echo mysqli_fetch_assoc($contagem)['total'];
                                ?>
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card alerta-card">
                        <div class="card-body">
                            <h6>Fora de serviço</h6>
                            <h2>
                                <?php
                                $contagem = mysqli_query(
                                    $conexao,
                                    "SELECT COUNT(*) AS total FROM trens WHERE status = 1"
                                );
                                echo mysqli_fetch_assoc($contagem)['total'];
                                ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <h4>Frota de Trens</h4>

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
                        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                            <?php while ($trem = mysqli_fetch_assoc($resultado)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($trem['codigo']) ?></td>
                                    <td><?= htmlspecialchars($trem['nome']) ?></td>
                                    <td><?= htmlspecialchars($trem['linha']) ?></td>
                                    <td><?= htmlspecialchars($trem['destino']) ?></td>
                                    <td><?= (int) $trem['velocidade'] ?> km/h</td>

                                    <td>
                                        <?php
                                        $statusNomes = [
                                            3 => 'Operando',
                                            2 => 'Manutenção',
                                            1 => 'Fora de serviço'
                                        ];

                                        echo $statusNomes[$trem['status']] ?? 'Desconhecido';
                                        ?>
                                    </td>

                                    <?php if ($podeGerenciar): ?>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalTrem"
                                                data-id="<?= (int) $trem['id'] ?>"
                                                data-codigo="<?= htmlspecialchars($trem['codigo'], ENT_QUOTES) ?>"
                                                data-nome="<?= htmlspecialchars($trem['nome'], ENT_QUOTES) ?>"
                                                data-linha="<?= htmlspecialchars($trem['linha'], ENT_QUOTES) ?>"
                                                data-destino="<?= htmlspecialchars($trem['destino'], ENT_QUOTES) ?>"
                                                data-velocidade="<?= (int) $trem['velocidade'] ?>"
                                                data-status="<?= (int) $trem['status'] ?>">
                                                Editar
                                            </button>

                                            <a href="../funcoes/excluir_trens.php?id=<?= (int) $trem['id'] ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tem certeza que deseja excluir o trem <?= htmlspecialchars($trem['nome'], ENT_QUOTES) ?>?');">
                                                Excluir
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= $podeGerenciar ? 7 : 6 ?>" class="text-center">
                                    Nenhum trem cadastrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <?php if ($podeGerenciar): ?>
        <?php include '../components/modal-trens.php'; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
