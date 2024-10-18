<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido | Agro Malandrin</title>
</head>

    <?php
        include_once 'includes/header.php';
        require_once 'includes/dbconnect.php';
    ?>
<body>
    <div class="container justify-content-center">
        <div class="row"> 
            <div class="col-md-6 mx-auto"> 
                <div class="box">
                    <br><h3>Cadastro de Pedidos</h3><br>
                    
                    <form action="" method="POST">
                            
                        <input type="hidden" name="id_ped" value="">

                        <input type="hidden" name="data_ped" value="">

                        <label for="endereco_entrega">Endereço de Entrega:</label><br>
                        <input type="text" name="endereco_entrega" value="" required class="form-control"><br>

                        <label for="data_entrega_ped">Data de Entrega:</label><br>
                        <input type="date" name="data_entrega_ped" value="" class="form-control"><br>

                        <label for="id_cli">Cliente:</label>
                        <select name="id_cli" required class="form-control">
                            <option value="">Selecione</option>
                            <?php

                            ?>
                        </select><br>

                        <label for="id_usu">Usuario:</label><br>
                        <input name="id_usu" value="" class="form-control"><br>

                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </form><br><br>

                    <h3>Pedidos Realizados</h3>
                </div>
            </div>    
        </div>
    </div>
</body>
</html>