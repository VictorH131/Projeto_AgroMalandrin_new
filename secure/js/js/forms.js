function formhash(form, password) {

    // elemento input que sera o campo para a senha com hash
    var p = document.createElement("input");

    // adicionando novo elemento ao formulário
    form.appendChild(p);

    p.name = "p";
    p.type = "hiden";
    p.value = hex_sha512(password.value);

    // verificar se a senha em texto simples será enviada
    password.value = "";

    // envie o formulario
    form.submit();
}

function regformhash(form, uid, email, password, conf) {

    // conferir se cada campo tem um valor
    if(uid.value == '' ||
       email.value == '' ||
       password.value == '' ||
       conf.value == '') {

        alert('You must provide all the requested details. Please try again');

        return false;
    }

       // verifique o nome do usuario
    re = /^\w+$/; 

    if(!re.test(form.username.value)) {

        alert("Username must contain only letters, numbers and underscores. Please try again"); 

        form.username.focus();

        return false;
    }

    // conferindo se a senha é comprida o suficiente com no mínimo de 6 caracteres
    // a verificação sera duplicada abaixo, sendo um cuidado extra para dar orientações extra ao usuario
    if(password.value.length<6) {
        alert('Passwords must be at least 6 characters long. Please try again');

        form.password.focus();

        return false;
    }
       
    // pelo menos um numero, uma letra maiuscula e minuscula, 6 caracteres no minimo
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;

    if(!re.test(password.value)) {
        alert('Passwords must contain at least one number, one lowercase and one uppercase letter. Please try again');

        return false;
    }

    // verfificando se a senha e a confirmação são as mesmas
    if(password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');

        form.password.focus();

        return false;
    }

    // criando um novo elemento de input no qual será o campo para a senha hash
    var p = document.createElement("input");
    
    // Adicionando um novo elemento ao nosso formulário
    form.appendChild(p);

    p.name = "p";

    p.type = "hidden";

    p.value = hex_sha512(password.value);

    // Cuidado para não deixar que a senha em texto simples não seja enviada
    password.value = "";

    conf.value = "";

    // finalizando para enviar o formulario
    form.submit();

    return true;

}