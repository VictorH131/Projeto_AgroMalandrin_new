<?php
    session_start();
    session_destroy();  // Encerra a sessão atual
    header('Location: login.php');  // Redireciona para a página de login
    exit;
?>
