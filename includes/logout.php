<?php
    session_start();
    session_destroy();  // Sai
    header('Location: login.php');  // volta para o inicio
    exit;
    
?>
