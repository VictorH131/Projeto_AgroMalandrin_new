<?php   
   $localhost = "31.170.167.153";
   $username = "u335479363_alunos4";
   $password = '$en4C2024';
   $dbname = "u335479363_alunos4";

   $con = mysqli_connect($localhost, $username, $password, $dbname); 
   if (!$con) 
   {
      die ("Falha na Conexão: " .$con->connect_error); 
   }  
?>