<?php
    session_start();
    session_destroy();  // Sai
    header('Location: ../index.php');  // volta para o inicio
    exit;
    
?>
