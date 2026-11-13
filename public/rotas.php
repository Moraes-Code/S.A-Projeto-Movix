
<?php
session_start();
require_once '../infra/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$perfil = (int) ($_SESSION['id_perfil'] ?? 0);
$podeGerenciar = ($perfil === 1);

if (!isset($_SESSION['token_rotas'])) {
    $_SESSION['token_rotas'] = bin2hex(random_bytes(32));
}

function limpar($texto)
{
    return htmlspecialchars((string) ($texto ?? ''), ENT_QUOTES, 'UTF-8');
}

// PREPARAR CONSULTAS COM VERIFICAÇÃO DE ERRO
function prepararConsulta($conexao, $consulta)
{
    $sql = $conexao->prepare($consulta);

    if (!$sql) {
        die("Erro ao preparar consulta SQL: " . $conexao->error);
    }

    return $sql;
}

// CADASTRAR, EDITAR E EXCLUIR ROTAS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !$podeGerenciar ||
        !isset($_POST['token']) ||
        !hash_equals($_SESSION['token_rotas'], $_POST['token'])
    ) {
        exit('Acesso não permitido.');
    }

    try {

        // EXCLUIR ROTA
        if (isset($_POST['excluir_rota'])) {

            $id = (int) ($_POST['id_rota'] ?? 0);

            $sql = prepararConsulta(
                $conexao,
                "DELETE FROM rotas WHERE id_rota = ?"
            );

            $sql->bind_param('i', $id);
            $sql->execute();
            $sql->close();

            $_SESSION['notificacao'] = [
                'tipo' => 'success',
                'mensagem' => 'Rota excluída com sucesso!'
            ];

        } else {

            $nome = trim($_POST['nome_rota'] ?? '');
            $codigo = trim($_POST['codigo_rota'] ?? '');
            $origem = trim($_POST['origem'] ?? '');
            $destino = trim($_POST['destino'] ?? '');
            $distancia = (float) str_replace(',', '.', $_POST['distancia'] ?? '0');
            $tempo = trim($_POST['tempo_estimado'] ?? '');
            $status = $_POST['status'] ?? '';

            if (
                $nome === '' ||
                $codigo === '' ||
                $origem === '' ||
                $destino === '' ||
                $tempo === '' ||
                $distancia <= 0
            ) {
                throw new Exception('Preencha todos os campos corretamente.');
            }

            if (!in_array($status, ['Ativa', 'Inativa', 'Em manutenção'], true)) {
                throw new Exception('Status inválido.');
            }

            // EDITAR ROTA
            if (isset($_POST['editar_rota'])) {

                $id = (int) ($_POST['id_rota'] ?? 0);

                $sql = prepararConsulta(
                    $conexao,
                    "UPDATE rotas SET
                    nome_rota = ?,
                    codigo_rota = ?,
                    origem = ?,
                    destino = ?,
                    distancia = ?,
                    tempo_estimado = ?,
                    status = ?
                    WHERE id_rota = ?"
                );

                $sql->bind_param(
                    'ssssdssi',
                    $nome,
                    $codigo,
                    $origem,
                    $destino,
                    $distancia,
                    $tempo,
                    $status,
                    $id
                );

                $sql->execute();
                $sql->close();

                $_SESSION['notificacao'] = [
                    'tipo' => 'success',
                    'mensagem' => 'Rota atualizada com sucesso!'
                ];

            } else {

                // CADASTRAR ROTA
                $id_usuario = (int) $_SESSION['usuario_id'];

                $sql = prepararConsulta(
                    $conexao,
                    "INSERT INTO rotas
                    (nome_rota, codigo_rota, origem, destino,
                    distancia, tempo_estimado, status, id_usuario)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                );

                $sql->bind_param(
                    'ssssdssi',
                    $nome,
                    $codigo,
                    $origem,
                    $destino,
                    $distancia,
                    $tempo,
                    $status,
                    $id_usuario
                );

                $sql->execute();
                $sql->close();

                $_SESSION['notificacao'] = [
                    'tipo' => 'success',
                    'mensagem' => 'Rota cadastrada com sucesso!'
                ];
            }
        }

        header('Location: rotas.php');
        exit;

    } catch (mysqli_sql_exception $erro) {

        $mensagemErro = 'Não foi possível concluir a operação. Verifique se o código já existe ou se há trens vinculados à rota.';

        if ($erro->getCode() == 1062) {
            $mensagemErro = 'Esse código de rota já existe.';
        }

        $_SESSION['notificacao'] = [
            'tipo' => 'danger',
            'mensagem' => $mensagemErro
        ];

        header('Location: rotas.php');
        exit;

    } catch (Exception $erro) {

        $_SESSION['notificacao'] = [
            'tipo' => 'warning',
            'mensagem' => $erro->getMessage()
        ];

        header('Location: rotas.php');
        exit;
    }
}

// PESQUISAR ROTAS
$pesquisa = trim($_GET['pesquisa'] ?? '');

if ($pesquisa !== '') {

    $termo = "%{$pesquisa}%";

    $sql = prepararConsulta(
        $conexao,
        "SELECT r.*, u.nome AS nome_responsavel
        FROM rotas r
        INNER JOIN usuarios u ON r.id_usuario = u.id
        WHERE r.nome_rota LIKE ?
        OR r.codigo_rota LIKE ?
        OR r.origem LIKE ?
        OR r.destino LIKE ?
        ORDER BY r.id_rota DESC"
    );

    $sql->bind_param('ssss', $termo, $termo, $termo, $termo);

} else {

    $sql = prepararConsulta(
        $conexao,
        "SELECT r.*, u.nome AS nome_responsavel
        FROM rotas r
        INNER JOIN usuarios u ON r.id_usuario = u.id
        ORDER BY r.id_rota DESC"
    );
}

$sql->execute();

$resultadoRotas = $sql->get_result();

if (!$resultadoRotas) {
    die("Erro ao buscar as rotas: " . $sql->error);
}

$rotas = $resultadoRotas->fetch_all(MYSQLI_ASSOC);

$sql->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas - MOVIX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <?php include '../components/sidebar.php'; ?>

    <div class="main">

        <?php include '../components/notificacao.php'; ?>

        <div class="topbar">
            <h3>Gerenciamento de Rotas</h3>
        </div>

        <div class="content">

            <div class="table-container">

                <?php if ($podeGerenciar): ?>

                    <h4 class="mb-3">Cadastrar Rotas</h4>

                    <div class="form-container mb-4">

                        <form method="POST">

                            <input type="hidden" name="token" value="<?= limpar($_SESSION['token_rotas']) ?>">

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Nome da Rota</label>
                                    <input name="nome_rota" class="form-control"
                                        placeholder="Digite o nome da rota" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Código da Rota</label>
                                    <input name="codigo_rota" class="form-control"
                                        placeholder="Digite o código da rota" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Origem</label>
                                    <input name="origem" class="form-control"
                                        placeholder="Digite a estação de origem" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Destino</label>
                                    <input name="destino" class="form-control"
                                        placeholder="Digite a estação de destino" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Distância (km)</label>
                                    <input type="number" name="distancia" class="form-control"
                                        min="0.1" step="0.1" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Tempo Estimado</label>
                                    <input name="tempo_estimado" class="form-control"
                                        placeholder="Ex.: 2 horas" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Ativa">Ativa</option>
                                        <option value="Inativa">Inativa</option>
                                        <option value="Em manutenção">Em manutenção</option>
                                    </select>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button class="btn btn-primary">Salvar rota</button>
                            </div>

                        </form>

                    </div>

                <?php endif; ?>

                <h4 class="mb-3">Rotas cadastradas</h4>

                <form method="GET" class="row mb-4">

                    <div class="col-md-8">
                        <input name="pesquisa" class="form-control"
                            placeholder="Pesquisar rota"
                            value="<?= limpar($pesquisa) ?>">
                    </div>

                    <div class="col-auto">
                        <button class="btn btn-primary">Pesquisar</button>
                    </div>

                    <?php if ($pesquisa !== ''): ?>
                        <div class="col-auto">
                            <a href="rotas.php" class="btn btn-secondary">Limpar</a>
                        </div>
                    <?php endif; ?>

                </form>

                <div class="table-responsive">

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Nome da Rota</th>
                                <th>Código</th>
                                <th>Origem</th>
                                <th>Destino</th>
                                <th>Distância</th>
                                <th>Tempo Estimado</th>
                                <th>Responsável</th>
                                <th>Status</th>

                                <?php if ($podeGerenciar): ?>
                                    <th>Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (count($rotas) > 0): ?>

                                <?php $contador = 0; ?>

                                <?php foreach ($rotas as $r): ?>

                                    <tr class="<?= $contador >= 10 ? 'rota-oculta d-none' : '' ?>">

                                        <td><?= limpar($r['nome_rota']) ?></td>
                                        <td><?= limpar($r['codigo_rota']) ?></td>
                                        <td><?= limpar($r['origem']) ?></td>
                                        <td><?= limpar($r['destino']) ?></td>
                                        <td><?= number_format((float) $r['distancia'], 1, ',', '.') ?> km</td>
                                        <td><?= limpar($r['tempo_estimado']) ?></td>
                                        <td><?= limpar($r['nome_responsavel']) ?></td>
                                        <td><?= limpar($r['status']) ?></td>

                                        <?php if ($podeGerenciar): ?>

                                            <td class="text-nowrap">

                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rota<?= (int) $r['id_rota'] ?>">
                                                    Editar
                                                </button>

                                                <form method="POST" class="d-inline">

                                                    <input type="hidden" name="token"
                                                        value="<?= limpar($_SESSION['token_rotas']) ?>">

                                                    <input type="hidden" name="id_rota"
                                                        value="<?= (int) $r['id_rota'] ?>">

                                                    <button type="submit" name="excluir_rota" value="1"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Tem certeza que deseja excluir esta rota?');">
                                                        Excluir
                                                    </button>

                                                </form>

                                            </td>

                                        <?php endif; ?>

                                    </tr>

                                    <?php $contador++; ?>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="<?= $podeGerenciar ? 9 : 8 ?>" class="text-center">
                                        Nenhuma rota encontrada.
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

                <?php if (count($rotas) > 10): ?>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-primary" id="btnVerMaisRotas">
                            Ver mais
                        </button>
                    </div>

                <?php endif; ?>

            </div>

        </div>
    </div>

    <?php if ($podeGerenciar): ?>

        <?php foreach ($rotas as $r): ?>

            <div class="modal fade" id="rota<?= (int) $r['id_rota'] ?>" tabindex="-1">

                <div class="modal-dialog">
                    <div class="modal-content">

                        <form method="POST">

                            <div class="modal-header">
                                <h5 class="modal-title">Editar Rota</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <input type="hidden" name="token"
                                    value="<?= limpar($_SESSION['token_rotas']) ?>">

                                <input type="hidden" name="editar_rota" value="1">

                                <input type="hidden" name="id_rota"
                                    value="<?= (int) $r['id_rota'] ?>">

                                <label class="form-label">Nome da Rota</label>
                                <input name="nome_rota" class="form-control mb-3"
                                    value="<?= limpar($r['nome_rota']) ?>" required>

                                <label class="form-label">Código da Rota</label>
                                <input name="codigo_rota" class="form-control mb-3"
                                    value="<?= limpar($r['codigo_rota']) ?>" required>

                                <label class="form-label">Origem</label>
                                <input name="origem" class="form-control mb-3"
                                    value="<?= limpar($r['origem']) ?>" required>

                                <label class="form-label">Destino</label>
                                <input name="destino" class="form-control mb-3"
                                    value="<?= limpar($r['destino']) ?>" required>

                                <label class="form-label">Distância (km)</label>
                                <input type="number" name="distancia" class="form-control mb-3"
                                    min="0.1" step="0.1"
                                    value="<?= limpar($r['distancia']) ?>" required>

                                <label class="form-label">Tempo Estimado</label>
                                <input name="tempo_estimado" class="form-control mb-3"
                                    value="<?= limpar($r['tempo_estimado']) ?>" required>

                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">

                                    <?php foreach (['Ativa', 'Inativa', 'Em manutenção'] as $s): ?>

                                        <option value="<?= limpar($s) ?>"
                                            <?= $r['status'] === $s ? 'selected' : '' ?>>
                                            <?= limpar($s) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>

                                <button class="btn btn-primary">
                                    Salvar alterações
                                </button>

                            </div>

                        </form>

                    </div>
                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const btnVerMaisRotas = document.getElementById('btnVerMaisRotas');

        if (btnVerMaisRotas) {

            btnVerMaisRotas.addEventListener('click', function () {

                document.querySelectorAll('.rota-oculta').forEach(function (rota) {
                    rota.classList.toggle('d-none');
                });

                this.innerText = this.innerText === 'Ver mais' ? 'Ver menos' : 'Ver mais';

            });

        }
    </script>

</body>
</html>
