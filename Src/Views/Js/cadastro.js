const form = document.getElementById("formLogin");

form.addEventListener("submit", function(event){

    event.preventDefault();

    const nome = document.getElementById("nome").value;
    const email = document.getElementById("email").value;
    const senha = document.getElementById("senha").value;

    if(nome.length < 6){

        alert("O nome precisa ter no mínimo 6 caracteres!");
        return;

    }

    if(!email.includes("@")){

        alert("Digite um e-mail válido!");
        return;

    }

    if(senha.length < 6){

        alert("A senha precisa ter no mínimo 6 caracteres!");
        return;

    }

    window.location.href = "login.html";

});