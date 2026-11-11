<?php
$notificacao = $_SESSION['notificacao'] ?? null;
unset($_SESSION['notificacao']);

if ($notificacao):
    $tipo = $notificacao['tipo'] ?? 'success';
    $mensagem = $notificacao['mensagem'] ?? '';

    $cores = [
        'success' => '#198754',
        'danger' => '#dc3545',
        'warning' => '#ffc107'
    ];

    $icones = [
        'success' => 'fa-check',
        'danger' => 'fa-trash',
        'warning' => 'fa-exclamation'
    ];

    $cor = $cores[$tipo] ?? $cores['success'];
    $icone = $icones[$tipo] ?? $icones['success'];
?>

<div id="notificacaoMovix" class="notificacao-movix">
    <div class="notificacao-icone" style="background: <?= $cor ?>">
        <i class="fa-solid <?= $icone ?>"></i>
    </div>

    <div class="notificacao-texto">
        <strong>
            <?= $tipo === 'danger' ? 'Ação realizada!' : ($tipo === 'warning' ? 'Atenção!' : 'Sucesso!') ?>
        </strong>
        <span><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></span>
    </div>

    <button type="button" onclick="fecharNotificacao()" class="notificacao-fechar">
        &times;
    </button>
</div>

<style>
.notificacao-movix {
    position: fixed;
    top: 20px;
    right: 25px;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 12px;
    width: 350px;
    max-width: calc(100% - 30px);
    padding: 15px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,.15);
    border-left: 5px solid <?= $cor ?>;
    animation: entrarNotificacao .4s ease;
    transition: opacity .4s, transform .4s;
}

.notificacao-icone {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.notificacao-texto {
    display: flex;
    flex-direction: column;
    gap: 3px;
    flex: 1;
    color: #333;
    font-size: 14px;
}

.notificacao-texto strong {
    font-size: 15px;
}

.notificacao-fechar {
    border: none;
    background: transparent;
    color: #777;
    font-size: 23px;
    cursor: pointer;
}

.notificacao-saindo {
    opacity: 0;
    transform: translateX(30px);
}

@keyframes entrarNotificacao {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>

<script>
const notificacaoMovix = document.getElementById('notificacaoMovix');

function fecharNotificacao() {
    if (notificacaoMovix) {
        notificacaoMovix.classList.add('notificacao-saindo');

        setTimeout(function () {
            notificacaoMovix.remove();
        }, 400);
    }
}

if (notificacaoMovix) {
    setTimeout(fecharNotificacao, 3000);
}
</script>

<?php endif; ?>