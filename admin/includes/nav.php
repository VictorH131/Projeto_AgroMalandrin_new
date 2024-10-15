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
 
  <!-- Ícone de sanduíche -->
  <span class="hamburger" onclick="toggleSidebar()">&#9776;</span>
 
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <ul class="nav flex-column">

      <li class="nav-item">
        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Inicial</a> <!-- Ícone de casa -->
      </li>

      <li class="nav-item">
        <a class="nav-link" href="forms_usu.php"><i class="fas fa-user"></i> Usuários</a> <!-- Novo ícone de clientes -->
      </li>

      <li class="nav-item">
        <a class="nav-link" href="forms_cli.php"><i class="fas fa-users"></i> Clientes</a> <!-- Novo ícone de clientes -->
      </li>

      <li class="nav-item">
        <a class="nav-link" href="forms_prod.php"><i class="fas fa-box"></i> Produtos</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="forms_serv.php"><i class="fas fa-cut"></i> Serviços</a> <!-- Ícone de barbearia -->
      </li>

      <li class="nav-item">
        <a class="nav-link" href="forms_forne.php"><i class="fas fa-truck"></i> Fornecedores</a>
      </li>


      <li class="nav-item">
        <a class="nav-link" href="forms_ordem_servico.php"><i class="fas fa-tools"></i> Ordem de Serviço</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href=""><i class="fas fa-box-open"></i> Itens da OS</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="forms_compra.php"><i class="fas fa-shopping-cart"></i> Compras</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="forms_itens_compra.php"><i class="fas fa-box"></i> itens da Compra</a>
      </li>


      <li class="nav-item">
        <a class="nav-link" href="forms_ped.php"><i class="fas fa-receipt"></i> Pedidos</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="forms_itens_ped.php"><i class="fas fa-clipboard-list"></i> itens do Pedido</a>
      </li>
      

    </ul>
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
