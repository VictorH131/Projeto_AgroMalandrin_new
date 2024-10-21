<?php
    session_start();
    
    
    if ($_SESSION['logado'] == false){
      header('Location: ../index.php');
      exit;
    }

    // pega o nome do usuário logado
    $nomeUsuario = $_SESSION['nome'];
    
  ?> 