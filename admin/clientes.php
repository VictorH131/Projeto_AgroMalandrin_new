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

<div class="container">
        <div class="row"> 
            <div class="col-md-6 mx-auto"> 

                <div class="box"> 

                    <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Tabela de Clientes</h3><br><br>

                        <form action="" method="post">

                            <input type="hidden" name="id_cliente" value="">

                            <label for="nome">Nome do Cliente:</label><br>
                            <input type="text" name="nome" value="" class="form-control"><br><br>

                            <label for="tipo_do_documento">Tipo do Documento:</label><br>
                                <select name="tipo_do_documento" required class="form-control">
                                    <div class="tipo_documento">
                                        <optgroup label="Documento">
                                            <option value="invalido">Selecione</option>
                                            <option value="cpf">CPF</option>
                                            <option value="rg">RG</option>
                    
                                        </optgroup>
                                    </div>
                                </select><br>

                            <label for="documento_cliente">Documento:</label><br>
                            <input type="text" name="documento_cliente" value="" required class="form-control"> <br><br>

                            <label for="data_nascimento">Data de Nascimento:</label><br>
                            <input type="date" name="data_nascimento" value="" required class="form-control"><br><br>

                            <label for="email_cliente">Email:</label><br>
                            <input type="email" name="email_cliente" value="" required class="form-control"><br><br>

                            <label for="cep">CEP:</label><br>
                            <input type="text" name="cep" value="" required class="form-control"><br><br>

                            <label for="rua">Rua:</label><br>
                            <input type="text" name="rua" value="" required class="form-control"><br><br>

                            <label for="bairro">Bairro:</label><br>
                            <input type="text" name="bairro" value="" required class="form-control"><br><br>

                            <label for="cidade">Cidade:</label><br>
                            <input type="text" name="cidade" value="" required class="form-control"><br><br>

                            <label for="telefone_cliente">Telefone:</label><br>
                            <input type="text" name="telefone_cliente" value="" required class="form-control"><br><br>

                            <label for="uf">UF:</label><br>
                            <select name="uf" required class="form-control"><br>
                                <option value="invalido">Selecione</option>
                                <?php

                                ?>
                            </select>
            
                            <br><br>

                        </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
            
