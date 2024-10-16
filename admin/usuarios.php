
<!DOCTYPE html>
<html lang="en">
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
    <div class="container">
        <div class="row"> 
            <div class="col-md-6 col-md-offset-3"> 

                <div class="box"> 

                    <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Add New User</h3>

                        <form action="" method="post">

                            <label for="firstname">Firstname</label>
                            <input type="text" name="firstname" id="firstname" class="form-control"><br>

                            <label for="lastname">Lastname</label>
                            <input type="text" name="lastname" id="lastname" class="form-control"><br>

                            <label for="address">Address</label>
                            <textarea rows="4" name="address" id="address" class="form-control"></textarea><br>

                            <label for="contact">Contact</label>
                            <input type="text" name="contact" id="contact" class="form-control"><br>

                            <br>

                            <input type="submit" name="addnew" class="btn btn-success" value="Add New">

                        </form>
                </div>
            </div>
        </div>
    </div>



</body>
</html>
