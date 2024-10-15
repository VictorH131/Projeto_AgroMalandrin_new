<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar com Menu Sanduíche</title>
  <link rel="stylesheet" href="styles/nav.css">
  <!-- Link do CSS do Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Link do CSS do Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    
  </style>
</head>
<body>
 

  
  <span class="hamburger" onclick="toggleSidebar()">&#9776;</span>
 
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <ul class="nav flex-column">
    
    <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none" >
      <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
      <span class="fs-4"><img src="../multimidia/logo/Logo_Head_mob.png" alt=""></span>
    </a>

   
      
      

      <li class="nav-item">
        <a class="nav-link" href="usuario.php"><i class="fa-solid fa-user"></i> Usuários</a> <!-- Novo ícone de clientes -->
      </li>

      <li class="nav-item">
        <a class="nav-link" href="cliente.php"><i class="fas fa-users"></i> Clientes</a> <!-- Novo ícone de clientes -->
      </li>

      <li class="nav-item">
        <a class="nav-link" href="produtos.php"><i class="fas fa-box"></i> Produtos</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="servico.php"><i class="fas fa-cut"></i> Serviços</a> <!-- Ícone de barbearia -->
      </li>

      <li class="nav-item">
        <a class="nav-link" href="fornec.php"><i class="fas fa-truck"></i> Fornecedores</a>
      </li>


      <li class="nav-item">
        <a class="nav-link" href="OS.php"><i class="fas fa-tools"></i> Ordem de Serviço</a>
      </li>

      

      <li class="nav-item">
        <a class="nav-link" href="compra.php"><i class="fas fa-shopping-cart"></i> Compras</a>
      </li>

      


      <li class="nav-item">
        <a class="nav-link" href="peddidos.php"><i class="fas fa-receipt"></i> Pedidos</a>
      </li>

     <!--
      <li class="nav-item">
        <a class="nav-link" href=""><i class="fas fa-box-open"></i> Itens da OS</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="itens_ped.php"><i class="fas fa-clipboard-list"></i> itens do Pedido</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="itens_compra.php"><i class="fas fa-box"></i> itens da Compra</a>
      </li>
      
      -->

    </ul>
    
    <br>
    <hr>
    <br>

    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fa-solid fa-gear"></i>
        <strong><?php echo $nomeUsuario; ?></strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        
        <li> <a class="nav-link" href="../index.php"><i class="fas fa-home"></i> Home </a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#" onclick="window.location.href='../secure/logout.php'"><i class="fa-solid fa-right-from-bracket"></i>Sair</a></li>
      </ul>
    </div>
  </div>
 
  <script>
    function toggleSidebar() {
      var sidebar = document.getElementById("sidebar");
      var content = document.getElementById("content");
      
      sidebar.classList.toggle("active");
      content.classList.toggle("active");
    }
  </script>
 
  <!-- Link do JS do Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
