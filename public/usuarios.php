<?php

require_once 'scripts/layout.php';
iniciarPagina(
    'Gerenciamento de Rotas',
    'Controle das rotas ferroviárias do sistema MOVIX',
    'rotas.php'
);

?>

<div class="table-container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4>Rotas Cadastradas</h4>

            <small class="text-muted">
                Gerenciamento das rotas ferroviárias
            </small>
        </div>

        <a
            href="cadastro.php?tipo=rota"
            class="btn btn-movix"
        >

            <i class="fa-solid fa-plus"></i>
            Nova Rota
        </a>

    </div>
    <div class="mb-4">
        <input
            type="text"
            class="form-control"
            placeholder="Pesquisar rota..."
        >
    </div>

    