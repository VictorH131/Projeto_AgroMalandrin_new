<!DOCTYPE html>

<html lang="pt-br">

<head>
    
    <title>CRUD</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> <!-- linkando o bootstrap -->
    <style type="text/css">
        .box{
            background: #f0f0f0; padding: 20px;} /* utilizando o css para mudar a cor e o espaçamento que a "caixa" irá tomar*/ 
    </style> </head>

<body>
    <div class="container"> <!-- chama uma função chamada container que ja coloca info de maneira automatica de outro arquivo -->

        <nav class="navbar navbar-default"> <!-- cria uma classe para o nav que também representa a div -->

            <div class="container-fluid"> <!-- cria uma classe que representa a div -->

                <div class="navbar-header"> <!-- cria uma classe que representa a div -->

                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="index.php">CRUD</a>
                </div> <!-- fechando o navbar-header -->

                <div id="navbar" class="navbar-collapse collapse">

                    <ul class="nav navbar-nav">

                        <li class="active"><a href="index.php">Home</a></li>
                        <li><a href="insert.php">Add User</a></li>
                        <li><a href="users.php">All Users</a></li>

                    </ul>

                </div> <!-- /.nav-collapse -->

            </div> <!-- /.container-fluid -->

        </nav>

    </div>

        

    </div>
</body>