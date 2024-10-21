<?php
include_once '../includes/internal/session.php';  // Verificando se você está logado
?>

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
                                <li><a href="compras.php"  class="dropdown-item" > <h1>Compras</h1> </a></li>
                                <li><a href="servicos.php"  class="dropdown-item" > <h1>Serviços</h1> </a></li>
                                <li><a href="fornecedor.php"  class="dropdown-item" > <h1>Fornecedor</h1> </a></li>
                                <li><a href="os.php"  class="dropdown-item" > <h1>Ordem de Serviço</h1> </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="itens_os.php">itens Ordem de Serviço</a></li>
                                <li><a class="dropdown-item" href="itens_compra.php">itens Compra</a></li>
                                <li><a class="dropdown-item" href="itens_pedido.php">itens Pedido</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <!-- link de saida -->
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i>
                                    <strong><?php echo $nomeUsuario; ?></strong>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-light text-small shadow" aria-labelledby="dropdownUser1">
                                    
                                    <li> <a class="nav-link" href="../index.php"><i class="fas fa-home"></i> Home </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="window.location.href='../includes/logoff.php'">Sair</a></li>
                                </ul>
                            </div>
                            
                        </li>
                        
                    </ul>
                    
                        
                        
                        
                    </div>
                </div>
            </div>
        </nav> 
    </div>