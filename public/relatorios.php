
<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../infra/conexao.php';

$perfil = $_SESSION['id_perfil'];
$podeGerenciar = ($perfil == 1 || $perfil == 2);

// Contagem dos registros
$sensores = $conexao->query("SELECT COUNT(*) AS total FROM sensores")->fetch_assoc()['total'];
$trens = $conexao->query("SELECT COUNT(*) AS total FROM trens")->fetch_assoc()['total'];
$rotas = $conexao->query("SELECT COUNT(*) AS total FROM rotas")->fetch_assoc()['total'];
$usuarios = $conexao->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];

// Variáveis do relatório
$tipo = '';
$resultado = null;
$mensagem = '';
$titulos = [];

// Gerar relatório
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $podeGerenciar) {

    $tipo = $_POST['tipo'] ?? '';

    switch ($tipo) {

        case 'sensores':
            $sql = "SELECT codigo, nome, tipo, localizacao, status FROM sensores";
            $titulos = ['Código', 'Nome', 'Tipo', 'Localização', 'Status'];
            break;

        case 'trens':
            $sql = "SELECT codigo, nome, linha, destino, velocidade, status FROM trens";
            $titulos = ['Código', 'Nome', 'Linha', 'Destino', 'Velocidade', 'Situação'];
            break;

        case 'usuarios':
            $sql = "SELECT usuarios.nome, usuarios.email, perfis.nome AS perfil
                    FROM usuarios
                    INNER JOIN perfis ON usuarios.id_perfil = perfis.id";
            $titulos = ['Nome', 'E-mail', 'Perfil'];
            break;

        case 'rotas':
            $sql = "SELECT codigo_rota, nome_rota, origem, destino, distancia, tempo_estimado, status FROM rotas";
            $titulos = ['Código', 'Nome', 'Origem', 'Destino', 'Distância', 'Tempo', 'Status'];
            break;

        default:
            $sql = '';
            $mensagem = 'Selecione um relatório válido.';
    }

    if ($sql != '') {

        $resultado = $conexao->query($sql);

        if (!$resultado) {
            $mensagem = 'Erro ao consultar o banco de dados.';
        } elseif ($resultado->num_rows == 0) {
            $mensagem = 'Nenhum registro encontrado.';
        }
    }
}

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

        <div class="content">

            <!-- Cards -->
            <div class="row g-4 mb-4">

                <div class="col-md-3">
                    <div class="card sensor-card">
                        <div class="card-body">
                            <h6>Sensores</h6>
                            <h2><?= $sensores ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card trem-card">
                        <div class="card-body">
                            <h6>Trens</h6>
                            <h2><?= $trens ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card alerta-card">
                        <div class="card-body">
                            <h6>Rotas</h6>
                            <h2><?= $rotas ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card usuario-card">
                        <div class="card-body">
                            <h6>Usuários</h6>
                            <h2><?= $usuarios ?></h2>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Filtros -->
            <div class="table-container mb-4">

                <h4>Filtros de Relatório</h4>

                <?php if ($podeGerenciar): ?>

                    <form method="POST">

                        <div class="row g-3 align-items-end">

                            <div class="col-md-4">
                                <label for="tipo" class="form-label">Tipo de relatório</label>

                                <select name="tipo" id="tipo" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <option value="sensores" <?= $tipo == 'sensores' ? 'selected' : '' ?>>Sensores</option>
                                    <option value="trens" <?= $tipo == 'trens' ? 'selected' : '' ?>>Trens</option>
                                    <option value="rotas" <?= $tipo == 'rotas' ? 'selected' : '' ?>>Rotas</option>
                                    <option value="usuarios" <?= $tipo == 'usuarios' ? 'selected' : '' ?>>Usuários</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-sm btn-outline-primary text-nowrap">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    Gerar relatório
                                </button>
                            </div>

                        </div>

                    </form>

                <?php else: ?>

                    <p>Você não possui permissão para gerar relatórios.</p>

                <?php endif; ?>

            </div>

            <!-- Resultado -->
            <?php if ($resultado && $resultado->num_rows > 0): ?>

                <div class="table-container">

                    <h4>Resultado do Relatório</h4>

                    <div class="col-md-5 mb-3">

                        <label for="pesquisa" class="form-label">Pesquisar resultados</label>

                        <input type="text" id="pesquisa" class="form-control"
                            placeholder="Digite para pesquisar...">

                    </div>

                    <div class="table-responsive">

                        <table class="table">

                            <thead>
                                <tr>
                                    <?php foreach ($titulos as $titulo): ?>
                                        <th><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>

                            <tbody id="tabelaResultados">

                                <?php while ($linha = $resultado->fetch_assoc()): ?>

                                    <tr>

                                        <?php foreach ($linha as $campo => $valor): ?>

                                            <td>
                                                <?php

                                                if ($tipo == 'trens' && $campo == 'status') {

                                                    if ($valor == 3) {
                                                        $valor = 'Operando';
                                                    } elseif ($valor == 2) {
                                                        $valor = 'Manutenção';
                                                    } elseif ($valor == 1) {
                                                        $valor = 'Fora de serviço';
                                                    } else {
                                                        $valor = 'Indefinido';
                                                    }

                                                }

                                                echo htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');

                                                ?>
                                            </td>

                                        <?php endforeach; ?>

                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                    <p class="text-muted">
                        Total de registros: <?= $resultado->num_rows ?>
                    </p>

                </div>

            <?php elseif ($mensagem != ''): ?>

                <div class="table-container">
                    <div class="alert alert-warning">
                        <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>

            <?php endif; ?>

        </div>

    </div>

    <!-- Pesquisa -->
    <script>

        document.getElementById('pesquisa')?.addEventListener('input', function () {

            let pesquisa = this.value.toLowerCase();

            let linhas = document.querySelectorAll('#tabelaResultados tr');

            linhas.forEach(function (linha) {

                let texto = linha.innerText.toLowerCase();

                if (texto.includes(pesquisa)) {
                    linha.style.display = '';
                } else {
                    linha.style.display = 'none';
                }

            });

        });

    </script>

</body>

</html>
