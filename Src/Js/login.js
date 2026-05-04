const form = document.getElementById("formLogin");

form.addEventListener("submit", function(event) {
  event.preventDefault();

  const email = document.getElementById("email").value;
  const senha = document.getElementById("senha").value;

  // validação básica
  if (email === "" || senha === "") {
    alert("Preencha todos os campos!");
    return;
  }

  // login fake (simulação)
  if (email === "admin@movix.com" && senha === "1234") {
    alert("Login realizado com sucesso!");

    // redireciona
    window.location.href = "dashboard.html";
  } else {
    alert("E-mail ou senha incorretos!");
  }
});