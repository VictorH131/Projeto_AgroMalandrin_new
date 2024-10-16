<?php
    include_once 'includes/header.php';
    require_once 'includes/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cliente | Agro Malandrin</title>

    </head>
<body>
    <form action="clientes.php" method="POST">
        <input type="hidden" name="id_cliente" value="<?= isset($_POST['id_cliente']) ? (int) $_POST['id_cliente'] : -1 ?>">

            <label for="nome">Nome do Cliente:</label>
    </form>
</body>
</html>