<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="card-title">Clima em Joinville</h5>

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

        <div class="alert alert-warning mt-3 mb-0 d-none" id="clima-erro">
            Não foi possível carregar o clima. Tente novamente mais tarde.
        </div>
    </div>
</div>

<script>
// Busca as informações da API de clima
function carregarClima() {
    fetch('../funcoes/clima.php')
        .then(function (resposta) {
            if (!resposta.ok) {
                throw new Error('Erro na consulta');
            }

            return resposta.json();
        })
        .then(function (dados) {
            if (dados.ok !== true) {
                throw new Error('Não foi possível consultar o clima');
            }

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
        });
}

// Consulta quando a página abre e atualiza a cada 10 minutos
carregarClima();
setInterval(carregarClima, 10 * 60 * 1000);
</script>
