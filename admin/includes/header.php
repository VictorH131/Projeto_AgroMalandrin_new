<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- conectando com o css-->
    <link href="parfum/estilo_adm.css" rel="stylesheet" type="text/css">

    
    <!-- colocando o icone da pagina-->
    <link rel="shortcut icon" href="../multimidia/icones/planta.png" type="image/x-icon">


    <!-- linkando ao Bootstrap v5.1 -->
        <!--CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   
</head>
 
<body>
        
    <div id="header">
        <!-- linkando ao Bootstrap v5.1 -->
            <!--JAVASCRIP-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


        <!-- criando o header -->

        <nav class="navbar navbar-expand-lg navbar-light " id="header-int">
            <div class="container-fluid">
            
                
                <picture>
                    <source media="(min-width: 992px)" srcset="../multimidia/logo/logo_Head.png">
                    <img src="../multimidia/logo/logo_Head_mob.png" alt="logo Agro malandrin" id="logo">
                </picture>
            
                
                <button class="navbar-toggler bg-light" id="head-mob" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon bg-light" ></span>
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                
                    <div class="offcanvas-header lex-grow-1">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel"> Menu </h5>
                        
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <!-- criando o nav -->
                    <div class="offcanvas-body" id="navbar">
                    <ul class="" >
                        
                        


                    </ul>

                    <ul class="navbar-nav justify-content-evenly  flex-grow-1 pe-3" id="itens-navbar">
                        <li class="nav-item">
                            <!-- home -->
                            <a class="nav-link" aria-current="page" id="nav" href="principal.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"  id="nav"aria-expanded="false">Tabelas</a>
                            <ul class="dropdown-menu" >
                                <!-- links do nav -->
                                <li><a href="usuarios.php"  class="dropdown-item" > <h1>Usuarios</h1> </a></li>
                                <li><a href="clientes.php"  class="dropdown-item" > <h1>Clientes</h1> </a></li>
                                <li><a href="pedidos.php"  class="dropdown-item" > <h1>Pedidos</h1> </a></li>
                                <li><a href="produtos.php"  class="dropdown-item" > <h1>Produtos</h1> </a></li>
                                <li><a href="compra.php"  class="dropdown-item" > <h1>compra</h1> </a></li>
                                <li><a href="produtos.php"  class="dropdown-item" > <h1>servico</h1> </a></li>
                                <li><a href="OS.php"  class="dropdown-item" > <h1>Ordem de Servico</h1> </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <!-- link de saida -->
                            <a class="nav-link" id="nav" href="../includes/logoff">SAIR</a>
                        </li>
                        
                    </ul>
                    
                        
                        <div class="d-flex " id="login">
                            <!-- colocando o botão para poder logar -->
                            
                        </div>
                        
                    </div>
                </div>
            </div>
        </nav> 
    </div>