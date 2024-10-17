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

    <?php
    include_once 'includes/header.php';
    require_once 'includes/dbconnect.php';
    ?>

<body>

<div class="container">
        <div class="row"> 
            <div class="col-md-6 mx-auto"> 

                <div class="box"> 

                    <br><h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Tabela de Clientes</h3><br>

                        <form action="" method="POST">

                            <input type="hidden" name="id_cliente" value="">

                            <label for="nome_cli">Nome do Cliente:</label><br>
                            <input type="text" name="nome_cli" value="" class="form-control"><br>

                            <label for="tipo_do_documento_cli">Tipo do Documento:</label><br>
                                <select name="tipo_do_documento_cli" required class="form-control">
                                    <div class="tipo_documento_cli">
                                        <optgroup label="Documento">
                                            <option value="invalido">Selecione</option>
                                            <option value="cpf">CPF</option>
                                            <option value="rg">RG</option>
                    
                                        </optgroup>
                                    </div>
                                </select><br>

                            <label for="documento_cli">Documento:</label><br>
                            <input type="text" name="documento_cli" value="" required class="form-control"><br>

                            <label for="data_nascimento">Data de Nascimento:</label><br>
                            <input type="date" name="data_nascimento" value="" required class="form-control"><br>

                            <label for="email_cli">Email:</label><br>
                            <input type="email" name="email_cli" value="" required class="form-control"><br>

                            <label for="cep">CEP:</label><br>
                            <input type="text" name="cep" value="" required class="form-control"><br>

                            <label for="rua">Rua:</label><br>
                            <input type="text" name="rua" value="" required class="form-control"><br>

                            <label for="bairro">Bairro:</label><br>
                            <input type="text" name="bairro" value="" required class="form-control"><br>

                            <label for="cidade">Cidade:</label><br>
                            <input type="text" name="cidade" value="" required class="form-control"><br>

                            <label for="telefone_cli">Telefone:</label><br>
                            <input type="text" name="telefone_cli" value="" required class="form-control"><br>

                            <label for="uf">UF:</label><br>
                            <select name="uf" required class="form-control"><br>
                                <option value="invalido">Selecione</option>
                                <?php

                                ?>
                            </select><br>
            
                            <button type="submit"> </button>
                        </form><br><br>

                        <h3>Tabela de Clientes Ativos/Não Ativos</h3>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
            
