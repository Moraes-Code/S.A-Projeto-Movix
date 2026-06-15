function entrar() {
    window.location.href = "public/cadastro.html";
}

document.addEventListener("DOMContentLoaded", () => {

    const formulario = document.getElementById("Login");
    const emailInput = document.getElementById("email");
    const senhaInput = document.getElementById("senha");
    const nomeInput = document.getElementById("nome");

    if (!formulario) return;

    // TELA DE CADASTRO
    if (nomeInput) {

        formulario.addEventListener("submit", (event) => {
            event.preventDefault();

            const nome = nomeInput.value.trim();
            const email = emailInput.value.trim();
            const senha = senhaInput.value;

            if (nome.length <= 3) {
                alert("O nome deve conter mais de 3 caracteres.");
                nomeInput.focus();
                return;
            }

            if (
                !email.includes("@") ||
                email.indexOf("@") === 0 ||
                email.lastIndexOf("@") === email.length - 1
            ) {
                alert("Por favor, insira um e-mail válido contendo '@'.");
                emailInput.focus();
                return;
            }

            if (senha.length < 6) {
                alert("A senha deve conter no mínimo 6 caracteres.");
                senhaInput.focus();
                return;
            }

            alert("Cadastro realizado com sucesso!");
            console.log("Dados enviados:", { nome, email, senha });

            window.location.href = "dashboard.html";
        });

    }

    // TELA DE LOGIN
    else {

        formulario.addEventListener("submit", (event) => {
            event.preventDefault();

            const email = emailInput.value.trim();
            const senha = senhaInput.value;

            if (!email.includes("@")) {
                alert("Por favor, insira um e-mail válido.");
                emailInput.focus();
                return;
            }

            if (senha.length < 6) {
                alert("A senha deve conter no mínimo 6 caracteres.");
                senhaInput.focus();
                return;
            }

            alert("Login efetuado com sucesso! Entrando no sistema...");
            console.log("Login efetuado por:", email);

            window.location.href = "dashboard.html";
        });

    }
});