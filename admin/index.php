<?php
    include_once '../secure/verifica_login/session.php';  // Verificando se você está logado
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../secure/verifica_login/session.php">
   <title>Index | Agro Malandrin</title>
    <!-- colocando o icone da pagina-->
    <link rel="shortcut icon" href="../multimidia/icones/planta.png" type="image/x-icon">

   <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        flex-direction: column;
    }

    .welcome {
        margin-bottom: 20px;
        text-align: center;
    }

    .welcome h1 {
        font-size: 24px;
        color: #000;
    }

    .menu-block {
        background-color: #034F0A;
        padding: 20px;
        border-radius: 10px; /* Borda arredondada */
        width: 300px;
        text-align: center;
        box-shadow: 0 4px 10px #000;
    }

    .menu-item h2 {
        color: #fff;
        font-size: 20px;
        margin-bottom: 5px;
    }

    .menu-item p {
        margin-top: 5px;
        color: #aaa;
        font-size: 14px;
    }

    .menu-item a {
        text-decoration: none;
        color: #fff;
        font-weight: bold;
        transition: color 0.2s ease-in-out;
    }

    .menu-item a:hover {
        color: #FFF086;
    }

    hr {
        border: 1px solid #fff;
        margin: 10px 0;
    }

     button {
         padding: 10px 20px;
         background-color: #ad190e;
         color: #fff;
         border: none;
         border-radius: 10px; /* Borda arredondada */
         cursor: pointer;
         font-size: 16px;
         transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out;
         position: fixed;
         top: 20px;
         right: 20px;
         z-index: 1000;
         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
     }

     button:hover {
         background-color: #9e1111;
         transform: scale(1.1);
     }

     button:active {
         transform: scale(0.95);
         background-color: #bbb;
     }
   </style>
</head>
<?php 
    include_once "includes/nav.php"; 
?>
<body>

    <!-- Mensagem de boas-vindas com nome do usuário logado -->
    <div class="welcome">
        <h1>Bem-vindo : <?php echo $nomeUsuario; ?></h1>
    </div>

    <!-- Bloco com as opções -->
    <div class="menu-block">
        <div class="menu-item">
            <a href="usuario.php"><h2>+Usuário</h2></a>
            <p><a href="usuario.php">Cadastrar/Editar</a></p>
        </div>

        <div class="menu-item">
            <a href="cliente.php"><h2>+Clientes</h2></a>
            <p><a href="cliente.php">Cadastrar/Editar</a></p>
        </div>
        <hr>
        <div class="menu-item">
            <a href="servico.php"><h2>+Serviços</h2></a>
            <p><a href="servico.php">Cadastrar/Editar</a></p>
        </div>
        <hr>
        <div class="menu-item">
            <a href="produtos.php"><h2>+Produtos</h2></a>
            <p><a href="produtos.php">Cadastrar/Editar</a></p>
        </div>
        <hr>
        <div class="menu-item">
            <a href="fornec.php"><h2>+Fornecedores</h2></a>
            <p><a href="fornec.php">Cadastrar/Editar</a></p>
        </div>
    </div>
   
   <!-- Botão Sair fixado no canto superior direito -->
   

</body>
</html>
