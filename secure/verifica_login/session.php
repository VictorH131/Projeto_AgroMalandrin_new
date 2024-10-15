<?php
    session_start();
    
    
    if ($_SESSION['logado'] == false){
      header('Location: ../index.php');
      exit;
    }

    // Recuperar o nome do usuário da sessão
    $nomeUsuario = $_SESSION['nome'];
  ?>