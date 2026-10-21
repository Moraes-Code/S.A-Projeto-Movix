
<div class="modal fade" id="modalTrem" tabindex="-1" aria-labelledby="modalTremLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTremLabel">Editar Trem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <form action="trens.php" method="POST">
                    <input type="hidden" id="modal-id" name="id">

                    <div class="mb-3">
                        <label for="modal-codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="modal-codigo" name="codigo" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="modal-nome" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-linha" class="form-label">Linha</label>
                        <input type="text" class="form-control" id="modal-linha" name="linha" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-destino" class="form-label">Destino</label>
                        <input type="text" class="form-control" id="modal-destino" name="destino" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-velocidade" class="form-label">Velocidade (km/h)</label>
                        <input type="number" class="form-control" id="modal-velocidade" name="velocidade" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-status" class="form-label">Status</label>
                        <select class="form-control" id="modal-status" name="status" required>
                            <option value="3">Operando</option>
                            <option value="2">Manutenção</option>
                            <option value="1">Fora de serviço</option>
                        </select>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalTrem');

    modal.addEventListener('show.bs.modal', function (event) {
        const botao = event.relatedTarget;

        document.getElementById('modal-id').value = botao.dataset.id;
        document.getElementById('modal-codigo').value = botao.dataset.codigo;
        document.getElementById('modal-nome').value = botao.dataset.nome;
        document.getElementById('modal-linha').value = botao.dataset.linha;
        document.getElementById('modal-destino').value = botao.dataset.destino;
        document.getElementById('modal-velocidade').value = botao.dataset.velocidade;
        document.getElementById('modal-status').value = botao.dataset.status;
    });
});
</script>
