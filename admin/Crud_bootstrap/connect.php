<?php
    $localhost ="localhost";
    $username ="root";
    $password ="";
    $dbname ="samueldb";

    $con = mysqli_connect($localhost,$username,$password,$dbname); // cria uma varivel que armazena uma conexão

    if($con->connect_error) { die("connection failed :".$con->connect_error);} // se o codigo der algum erro, sera chamada a função "die" que apresentara uma mensagem de erro
