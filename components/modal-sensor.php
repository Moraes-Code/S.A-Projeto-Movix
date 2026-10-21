
<div class="modal fade" id="modalSensor" tabindex="-1" aria-labelledby="modalSensorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalSensorLabel">Editar Sensor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <form action="sensores.php" method="POST">
                    <input type="hidden" id="modal-id" name="id">

                    <div class="mb-3">
                        <label for="modal-codigo" class="form-label">ID</label>
                        <input type="text" class="form-control" id="modal-codigo" name="codigo" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="modal-nome" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-tipo" class="form-label">Tipo</label>
                        <select class="form-control" id="modal-tipo" name="tipo" required>
                            <option value="Temperatura">Temperatura</option>
                            <option value="Vibração">Vibração</option>
                            <option value="Pressão">Pressão</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="modal-localizacao" class="form-label">Localização</label>
                        <input type="text" class="form-control" id="modal-localizacao" name="localizacao" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-status" class="form-label">Status</label>
                        <select class="form-control" id="modal-status" name="status" required>
                            <option value="Online">Online</option>
                            <option value="Atenção">Atenção</option>
                            <option value="Offline">Offline</option>
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
    const modal = document.getElementById('modalSensor');

    modal.addEventListener('show.bs.modal', function (event) {
        const botao = event.relatedTarget;

        document.getElementById('modal-id').value = botao.dataset.id;
        document.getElementById('modal-codigo').value = botao.dataset.codigo;
        document.getElementById('modal-nome').value = botao.dataset.nome;
        document.getElementById('modal-tipo').value = botao.dataset.tipo;
        document.getElementById('modal-localizacao').value = botao.dataset.localizacao;
        document.getElementById('modal-status').value = botao.dataset.status;
    });
});
</script>
