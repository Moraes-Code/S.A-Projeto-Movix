
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalUsuarioLabel">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <form action="usuarios.php" method="POST">
                    <input type="hidden" id="modal-id" name="id">

                    <div class="mb-3">
                        <label for="modal-nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="modal-nome" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="modal-email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="modal-cpf" name="cpf" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal-telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="modal-telefone" name="telefone">
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
    const modal = document.getElementById('modalUsuario');

    modal.addEventListener('show.bs.modal', function (event) {
        const botao = event.relatedTarget;

        document.getElementById('modal-id').value = botao.dataset.id;
        document.getElementById('modal-nome').value = botao.dataset.nome;
        document.getElementById('modal-email').value = botao.dataset.email;
        document.getElementById('modal-cpf').value = botao.dataset.cpf;
        document.getElementById('modal-telefone').value = botao.dataset.telefone;
    });
});
</script>
