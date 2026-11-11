<?php
require_once __DIR__ . '/../funcoes/protecao_admin.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrativo - MOVIX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css?v=2">
</head>

<body>

    <?php include '../components/sidebar.php'; ?>
    <div class="botao-sair">
        <a href="../index.php?logout=1">Sair</a>
    </div>

    </div>

    <div class="main">

        <div class="topbar">
            <div>
                <h3>Dashboard Administrativo</h3>

                <p class="mb-0">
                    Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Administrador') ?>
                </p>
            </div>
        </div>

        <div class="content">


            <div class="row g-4 mb-4">
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card shadow-sm border-0 p-4 h-100" style="min-height: 130px;">
                        <h5 class="card-title">Clima em Joinville</h5>

                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <h2 class="text-primary mb-0" id="clima-temperatura">-- °C</h2>
                                </div>

                                <div>
                                    <p class="fw-bold mb-1" id="clima-condicao">Carregando...</p>
                                    <small class="text-muted d-block" id="clima-local">Joinville, SC</small>
                                    <small class="text-muted d-block" id="clima-atualizacao"></small>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-atualizar-clima">
                                Atualizar
                            </button>
                        </div>

                        <div class="alert alert-warning mt-3 mb-0 d-none" id="clima-erro">
                            Não foi possível carregar o clima. Tente novamente mais tarde.
                        </div>
                    </div>
                </div>
            </div>


            <div class="mb-4">
                <h4>Painel de controle</h4>

                <p class="text-secondary">
                    Acesse as principais áreas de gerenciamento do MOVIX.
                </p>
            </div>

            <div class="row g-4">

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Usuários</h5>
                        <p>Gerencie os usuários cadastrados no sistema.</p>
                        <a href="usuarios.php" class="btn btn-light mt-auto">
                            Gerenciar usuários
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Monitoramento</h5>
                        <p>Acesse a área de acompanhamento ferroviário.</p>
                        <a href="monitoramento.php" class="btn btn-light mt-auto">
                            Acessar monitoramento
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Trens</h5>
                        <p>Consulte e gerencie os trens cadastrados.</p>
                        <a href="trens.php" class="btn btn-light mt-auto">
                            Gerenciar trens
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Sensores</h5>
                        <p>Acesse o cadastro e o gerenciamento dos sensores.</p>
                        <a href="sensores.php" class="btn btn-light mt-auto">
                            Gerenciar sensores
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card dashboard-card card-movix p-4 h-100">
                        <h5>Rotas</h5>
                        <p>Acesse a área de gerenciamento das rotas ferroviárias.</p>
                        <a href="rotas.php" class="btn btn-light mt-auto">
                            Gerenciar rotas
                        </a>
                    </div>
                </div>

            </div>

            <script>
                function carregarClima() {
                    const botao = document.getElementById('btn-atualizar-clima');

                    botao.disabled = true;

                    fetch('../funcoes/clima.php?t=' + new Date().getTime(), {
                        cache: 'no-store'
                    })
                        .then(function (resposta) {
                            if (!resposta.ok) throw new Error('Erro');
                            return resposta.json();
                        })
                        .then(function (dados) {
                            if (!dados.ok) throw new Error('Erro');

                            document.getElementById('clima-temperatura').textContent =
                                Math.round(dados.temperatura) + ' °C';

                            document.getElementById('clima-condicao').textContent = dados.condicao;
                            document.getElementById('clima-local').textContent = dados.local;

                            document.getElementById('clima-atualizacao').textContent =
                                'Atualizado em ' + dados.atualizado_em;

                            document.getElementById('clima-erro').classList.add('d-none');
                        })
                        .catch(function () {
                            document.getElementById('clima-temperatura').textContent = '-- °C';
                            document.getElementById('clima-condicao').textContent = 'Clima indisponível';
                            document.getElementById('clima-erro').classList.remove('d-none');
                        })
                        .finally(function () {
                            botao.disabled = false;
                        });
                }

                document.getElementById('btn-atualizar-clima').addEventListener('click', carregarClima);

                carregarClima();

                setInterval(carregarClima, 10 * 60 * 1000);
            </script>


</body>

</html>