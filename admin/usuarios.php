
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Usuario | Agro Malandrin</title>
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

                    <h3>Cadastro de Usuarios</h3>

                        <form action="" method="post">
                            
                            <input type="hidden" name="id_usuario" value="<?= isset($_POST['id_usuario']) ? (int) $_POST['id_usuario'] : -1 ?>"> <!--id-->
            
                            

                        </form>
                </div>
            </div>
        </div>
    </div>



</body>
</html>
