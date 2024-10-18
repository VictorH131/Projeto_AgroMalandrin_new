<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Agro Malandrin</title>
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
                    <br><h3>Cadastro de Produtos</h3><br>

                    <form action="" method="POST">
                        
                        <input type="hidden" name="id_prod" value="" required class="form-control">

                        <label for="nome_prod">Nome do Produto:</label><br>
                        <input type="text" name="nome_prod" value="" required class="form-control"><br>

                        <label for="marca">Marca:</label><br>
                        <input type="text" name="marca" value="" class="form-control"><br>

                        <label for="desc_prod">Desconto do Produto:</label><br>
                        <input type="text" name="desc_prod" value="" class="form-control"><br>

                        <label for="preco_venda">Preço de Venda:</label><br>
                        <input type="number" step="0.01" name="preco_venda" value="" required class="form-control"><br>

                        <label for="estoque_minimo">Estoque Mínimo:</label><br>
                        <input type="number" name="estoque_minimo" value="" required class="form-control"><br>

                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </form><br><br>

                    <h3>Tabela de Produtos</h3>
                </div>
            </div>
        </div>
    </div>
</body>
</html>