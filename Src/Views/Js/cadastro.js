document.getElementById("formCadastro").addEventListener("submit", function(event) {
  event.preventDefault(); 

  const nome = document.getElementById("nome").value;
  const email = document.getElementById("email").value;
  const senha = document.getElementById("senha").value;
  const confirmarSenha = document.getElementById("confirmarSenha").value;

  
  if (senha !== confirmarSenha) {
    alert("As senhas não coincidem!");
    return;
  }

  
  localStorage.setItem("email", email);
  localStorage.setItem("senha", senha);

  
  window.location.href = "login.html";
});