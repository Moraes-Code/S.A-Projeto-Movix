<div class="sidebar">
    <div class="logo-area">
        <img src="../assets/imgs/logo_movix_semfundoazul.png">
        <h3>MOVIX</h3>
        <small>Centro Ferroviário</small>
    </div>
    <ul>
        <li><a href="dashboard_usuario.php">Dashboard</a></li>
        <li><a href="monitoramento.php">Monitoramento</a></li>
        <li><a href="alertas.php">Alertas</a></li>
        <li><a href="sensores.php">Sensores</a></li>
        <li><a href="trens.php">Trens</a></li>
        <?php if (isset($_SESSION['id_perfil']) && $_SESSION['id_perfil'] == 1) { ?>
            <li><a href="usuarios.php">Usuários</a></li>
        <?php } ?>
        <li><a href="relatorios.php">Relatórios</a></li>
        <li><a href="rotas.php">Rotas</a></li>
        <div class="botao-sair">
            <a href="../index.php?logout=1">Sair</a>
        </div>
    </ul>
</div>