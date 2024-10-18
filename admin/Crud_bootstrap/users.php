<?php
    require_once 'connect.php'; // linkando com as info do connect
    require_once 'header.php';

    echo "<div class='container'>"; // chama as info da classe container

    if(isset($_POST['delete'])) { // condição criada para se o post for delete, apagar as info de um user
        $sql = "DELETE FROM users WHERE user_id=".$_POST['userid'];
        if($con->query($sql) === TRUE) { // condição criada para chamar um alerta de sucesso cason 
            echo "<div class='alert alert-success'>Successfully delete user</div>";

        }
    }

    $sql = "SELECT * FROM users"; // armazena um código select do sql em uma variavel
    $result = $con->query($sql); // armazena uma consulta em uma variavel
    if($result->num_rows > 0)  // condição que verifica se o numero de linhas é maior que 0
    {

        ?>
        <h2>List of all Users</h2>
        <table class="table table-bordered table-striped"> <!-- cria uma classe que alinha todos os termos abaixo nela -->
            <tr>
                <td>Firstname</td>
                <td>Lastname</td>
                <td>Address</td>
                <td>Contact</td>
                <td width="70px">Delete</td>
                <td width="70px">EDIT</td>
            </tr>
    <?php
        while($row = $result->fetch_assoc()) {  
            echo "<form action='' method='POST'>";
            echo "<input type='hidden' value='".$row['user_id']."' name='userid' />";

            echo "<tr>";
            echo "<td>".$row['firstname']."</td>";
            echo "<td>".$row['lastname']."</td>";
            echo "<td>".$row['address']."</td>";
            echo "<td>".$row['contact']."</td>";

            echo "<td><input type='submit' name='delete' value='Delete' class='btn btn-danger'/></td>"; // cria um input que serve como o botão de delete
            echo "<td><a href='edit.php?id=".$row['user_id']."' class='btn btn-info'>Edit</a></td>"; // cria um botão que serve como um botão de editar

            echo "</tr>";
            echo "</form>";
        }
        
        ?>

        </table>

    <?php

    }
    else
    {
        echo "<br><br>No Record Found";
    }

    ?>

    </div>

    <?php
        require_once 'footer.php';