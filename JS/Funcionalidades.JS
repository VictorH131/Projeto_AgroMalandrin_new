function copiarTexto() {
    var email = "agromalandrin@hotmail.com"; // Texto a ser copiado
    // Tenta usar o método clipboard para copiar
    navigator.clipboard.writeText(email)
    .then(() => {
        alert("Email copiado");
    })
    .catch(err => { 
        alert("Erro encontrado, Tente novamente mais tarde");   
    });
}