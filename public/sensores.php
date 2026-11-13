
<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../infra/conexao.php';

$perfil = (int) ($_SESSION['id_perfil'] ?? 3);
$podeGerenciar = ($perfil === 1 || $perfil === 2);
$mostrarTodos = isset($_GET['todos']) && $_GET['todos'] === '1';

if (!isset($_SESSION['token_sensores'])) {
    $_SESSION['token_sensores'] = bin2hex(random_bytes(32));
}

function limpar($texto)
{
    return htmlspecialchars((string) ($texto ?? ''), ENT_QUOTES, 'UTF-8');
}

// CADASTRAR, EDITAR E EXCLUIR SENSORES
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !$podeGerenciar ||
        !isset($_POST['token']) ||
        !hash_equals($_SESSION['token_sensores'], $_POST['token'])
    ) {
        exit('Você não tem permissão para realizar esta ação.');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');
    $localizacao = trim($_POST['localizacao'] ?? '');
    $status = trim($_POST['status'] ?? 'Online');

    $id_trem = (isset($_POST['id_trem']) && $_POST['id_trem'] !== '')
        ? (int) $_POST['id_trem']
        : null;

    $statusPermitidos = ['Online', 'Offline', 'Atenção'];
    $tiposPermitidos = ['Temperatura', 'Vibração', 'Pressão'];

    try {

        // EXCLUIR SENSOR
        if (isset($_POST['excluir_sensor'])) {

            if ($id <= 0) {
                throw new Exception('Sensor inválido.');
            }

            $stmt = $conexao->prepare(
                "DELETE FROM sensores WHERE id = ?"
            );

            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();

            $_SESSION['notificacao'] = [
                'tipo' => 'success',
                'mensagem' => 'Sensor excluído com sucesso!'
            ];

        } else {

            if (
                $codigo === '' ||
                $nome === '' ||
                $localizacao === '' ||
                !in_array($tipo, $tiposPermitidos, true) ||
                !in_array($status, $statusPermitidos, true)
            ) {
                throw new Exception('Preencha todos os campos corretamente.');
            }

            // VERIFICAR SE O TREM EXISTE
            if ($id_trem !== null) {

                $verifica = $conexao->prepare(
                    "SELECT id FROM trens WHERE id = ?"
                );

                $verifica->bind_param('i', $id_trem);
                $verifica->execute();

                $tremExiste = $verifica->get_result()->num_rows > 0;

                $verifica->close();

                if (!$tremExiste) {
                    throw new Exception('O trem selecionado não existe.');
                }
            }

            // ATUALIZAR SENSOR
            if (isset($_POST['atualizar_sensor'])) {

                if ($id <= 0) {
                    throw new Exception('Sensor inválido.');
                }

                $stmt = $conexao->prepare(
                    "UPDATE sensores
                    SET codigo = ?,
                    nome = ?,
                    tipo = ?,
                    localizacao = ?,
                    status = ?,
                    id_trem = ?
                    WHERE id = ?"
                );

                $stmt->bind_param(
                    'sssssii',
                    $codigo,
                    $nome,
                    $tipo,
                    $localizacao,
                    $status,
                    $id_trem,
                    $id
                );

                $stmt->execute();
                $stmt->close();

                $_SESSION['notificacao'] = [
                    'tipo' => 'success',
                    'mensagem' => 'Sensor atualizado com sucesso!'
                ];

            // CADASTRAR SENSOR
            } elseif (isset($_POST['salvar_sensor'])) {

                $stmt = $conexao->prepare(
                    "INSERT INTO sensores
                    (codigo, nome, tipo, localizacao, status, id_trem)
                    VALUES (?, ?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    'sssssi',
                    $codigo,
                    $nome,
                    $tipo,
                    $localizacao,
                    $status,
                    $id_trem
                );

                $stmt->execute();
                $stmt->close();

                $_SESSION['notificacao'] = [
                    'tipo' => 'success',
                    'mensagem' => 'Sensor cadastrado com sucesso!'
                ];
            }
        }

    } catch (mysqli_sql_exception $erro) {

        $_SESSION['notificacao'] = [
            'tipo' => 'danger',
            'mensagem' => ($erro->getCode() == 1062)
                ? 'Já existe um sensor com esse código.'
                : 'Não foi possível realizar a operação.'
        ];

    } catch (Exception $erro) {

        $_SESSION['notificacao'] = [
            'tipo' => 'warning',
            'mensagem' => $erro->getMessage()
        ];
    }

    header('Location: sensores.php' . ($mostrarTodos ? '?todos=1' : ''));
    exit;
}

// BUSCAR TRENS PARA O SELECT
$trens = [];

$resultadoTrens = $conexao->query(
    "SELECT id, codigo, nome FROM trens ORDER BY codigo"
);

if ($resultadoTrens) {
    $trens = $resultadoTrens->fetch_all(MYSQLI_ASSOC);
}

// CONTAR SENSORES
$sqlTotal = $conexao->query(
    "SELECT COUNT(*) AS total FROM sensores"
);

$totalSensores = (int) ($sqlTotal->fetch_assoc()['total'] ?? 0);

// LISTAR SENSORES E SEUS TRENS
$sqlSensores = "
    SELECT s.*,
    t.codigo AS codigo_trem,
    t.nome AS nome_trem
    FROM sensores s
    LEFT JOIN trens t ON s.id_trem = t.id
    ORDER BY s.id DESC
";

if (!$mostrarTodos) {
    $sqlSensores .= " LIMIT 10";
}

$resultadoSensores = $conexao->query($sqlSensores);

$sensores = $resultadoSensores
    ? $resultadoSensores->fetch_all(MYSQLI_ASSOC)
    : [];

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

        <?php include '../components/notificacao.php'; ?>

        <div class="topbar">
            <h3>Gerenciamento de Sensores</h3>
        </div>

        <div class="content">

            <?php if ($podeGerenciar): ?>

                <div class="form-container mb-4">

                    <h4 class="mb-3">Cadastrar Sensores</h4>

                    <form method="POST" action="sensores.php">

                        <input type="hidden" name="token"
                            value="<?= limpar($_SESSION['token_sensores']) ?>">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Código</label>
                                <input type="text" name="codigo" class="form-control"
                                    placeholder="Ex: SN001" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control"
                                    placeholder="Digite o nome do sensor" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tipo</label>
                                <select name="tipo" class="form-select" required>
                                    <option value="Temperatura">Temperatura</option>
                                    <option value="Vibração">Vibração</option>
                                    <option value="Pressão">Pressão</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Localização</label>
                                <input type="text" name="localizacao" class="form-control"
                                    placeholder="Ex: KM 25" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="Online">Online</option>
                                    <option value="Atenção">Atenção</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Trem relacionado</label>

                                <select name="id_trem" class="form-select">

                                    <option value="">Sem trem vinculado</option>

                                    <?php foreach ($trens as $trem): ?>

                                        <option value="<?= (int) $trem['id'] ?>">
                                            <?= limpar($trem['codigo'] . ' - ' . $trem['nome']) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>
                            </div>

                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" name="salvar_sensor" class="btn btn-primary">
                                Salvar sensor
                            </button>
                        </div>

                    </form>

                </div>

            <?php endif; ?>

            <div class="table-container">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h4 class="mb-0">Sensores Cadastrados</h4>

                    <span class="text-muted">
                        Total: <?= $totalSensores ?>
                    </span>

                </div>

                <div class="table-responsive">

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Localização</th>
                                <th>Status</th>
                                <th>Trem relacionado</th>

                                <?php if ($podeGerenciar): ?>
                                    <th>Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (count($sensores) > 0): ?>

                                <?php foreach ($sensores as $sensor): ?>

                                    <tr>

                                        <td><?= limpar($sensor['codigo']) ?></td>
                                        <td><?= limpar($sensor['nome']) ?></td>
                                        <td><?= limpar($sensor['tipo']) ?></td>
                                        <td><?= limpar($sensor['localizacao']) ?></td>
                                        <td><?= limpar($sensor['status']) ?></td>

                                        <td>
                                            <?= $sensor['id_trem'] === null
                                                ? 'Não vinculado'
                                                : limpar($sensor['codigo_trem'] . ' - ' . $sensor['nome_trem']) ?>
                                        </td>

                                        <?php if ($podeGerenciar): ?>

                                            <td class="text-nowrap">

                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#sensor<?= (int) $sensor['id'] ?>">
                                                    Editar
                                                </button>

                                                <form method="POST" class="d-inline">

                                                    <input type="hidden" name="token"
                                                        value="<?= limpar($_SESSION['token_sensores']) ?>">

                                                    <input type="hidden" name="id"
                                                        value="<?= (int) $sensor['id'] ?>">

                                                    <button type="submit" name="excluir_sensor" value="1"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Tem certeza que deseja excluir este sensor?');">
                                                        Excluir
                                                    </button>

                                                </form>

                                            </td>

                                        <?php endif; ?>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="<?= $podeGerenciar ? 7 : 6 ?>" class="text-center">
                                        Nenhum sensor cadastrado.
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

                <?php if ($totalSensores > 10): ?>

                    <div class="text-center mt-3">

                        <a href="sensores.php<?= $mostrarTodos ? '' : '?todos=1' ?>"
                            class="btn btn-outline-primary">

                            <?= $mostrarTodos ? 'Ver menos' : 'Ver mais' ?>

                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </div>
    </div>

    <?php if ($podeGerenciar): ?>

        <?php foreach ($sensores as $sensor): ?>

            <div class="modal fade" id="sensor<?= (int) $sensor['id'] ?>" tabindex="-1">

                <div class="modal-dialog">
                    <div class="modal-content">

                        <form method="POST"
                            action="sensores.php<?= $mostrarTodos ? '?todos=1' : '' ?>">

                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Editar Sensor</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <input type="hidden" name="token"
                                    value="<?= limpar($_SESSION['token_sensores']) ?>">

                                <input type="hidden" name="id"
                                    value="<?= (int) $sensor['id'] ?>">

                                <input type="hidden" name="atualizar_sensor" value="1">

                                <label class="form-label">Código</label>
                                <input type="text" class="form-control mb-3"
                                    name="codigo" value="<?= limpar($sensor['codigo']) ?>" required>

                                <label class="form-label">Nome</label>
                                <input type="text" class="form-control mb-3"
                                    name="nome" value="<?= limpar($sensor['nome']) ?>" required>

                                <label class="form-label">Tipo</label>

                                <select class="form-select mb-3" name="tipo" required>

                                    <?php foreach (['Temperatura', 'Vibração', 'Pressão'] as $tipo): ?>

                                        <option value="<?= limpar($tipo) ?>"
                                            <?= $sensor['tipo'] === $tipo ? 'selected' : '' ?>>
                                            <?= limpar($tipo) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                                <label class="form-label">Localização</label>
                                <input type="text" class="form-control mb-3"
                                    name="localizacao" value="<?= limpar($sensor['localizacao']) ?>" required>

                                <label class="form-label">Status</label>

                                <select class="form-select mb-3" name="status" required>

                                    <?php foreach (['Online', 'Atenção', 'Offline'] as $status): ?>

                                        <option value="<?= limpar($status) ?>"
                                            <?= $sensor['status'] === $status ? 'selected' : '' ?>>
                                            <?= limpar($status) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                                <label class="form-label">Trem relacionado</label>

                                <select name="id_trem" class="form-select">

                                    <option value="">Sem trem vinculado</option>

                                    <?php foreach ($trens as $trem): ?>

                                        <option value="<?= (int) $trem['id'] ?>"
                                            <?= (int) ($sensor['id_trem'] ?? 0) === (int) $trem['id'] ? 'selected' : '' ?>>

                                            <?= limpar($trem['codigo'] . ' - ' . $trem['nome']) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>

                                <button type="submit" class="btn btn-primary">
                                    Atualizar
                                </button>

                            </div>

                        </form>

                    </div>
                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
