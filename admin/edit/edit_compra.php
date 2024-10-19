<?php

require_once '../includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o ID da compra foi passado
if (!isset($_GET['id'])) {
    die("ID da compra não fornecido.");
}

$id_compra = $_GET['id'];

// Consultar os dados da compra
$stmt = $conn->prepare("SELECT c.*, u.nome_usu, f.nome_for FROM Compra c 
                        JOIN Usuario u ON c.id_usu = u.id_usu 
                        JOIN Fornecedor f ON c.id_for = f.id_for 
                        WHERE c.id_compra = ?");
$stmt->bind_param("i", $id_compra);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Compra não encontrada.");
}

$row = $result->fetch_assoc();

// Atualizar dados da compra
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["data_entrega_efetiva"])) {
    $data_entrega_efetiva = $_POST["data_entrega_efetiva"];

    // Preparar a atualização
    $update_stmt = $conn->prepare("UPDATE Compra SET data_entrega_efetiva = ? WHERE id_compra = ?");
    $update_stmt->bind_param("si", $data_entrega_efetiva, $id_compra);

    if ($update_stmt->execute()) {
        $success = "Data de entrega efetiva atualizada com sucesso.";
    } else {
        $aviso = "Erro ao atualizar data de entrega efetiva: " . $update_stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar - Compra | Agro Malandrin</title>
       
    <!-- colocando o icone da pagina-->
    <link rel="shortcut icon" href="../../multimidia/icones/planta.png" type="image/x-icon">


    <!-- linkando ao Bootstrap v5.1 -->
        <!--CSS-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>


<div class="container">
            <div class="row">
                <div class="col-md-9 mx-auto">
                    <div class="box">
                        <a href="../compras.php" class="btn btn-secondary">  voltar</a>
                    </div>
                </div>
            </div>
        </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box">
                    <br><h3>Editar Compra</h3><br>

                    <!-- Exibir mensagens de aviso ou sucesso -->
                    <?php if (!empty($aviso)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <!-- Formulário de Edição de Compras -->
                    <form action="edit_compra.php?id=<?= $id_compra ?>" method="POST">
                        <div class="form-group">
                            <label>ID da Compra:</label>
                            <p class="form-control-static"><?= htmlspecialchars($row['id_compra']) ?></p>
                        </div>

                        <div class="form-group">
                            <label>Fornecedor:</label>
                            <p class="form-control-static"><?= htmlspecialchars($row['nome_for']) ?></p>
                        </div>

                        <div class="form-group">
                            <label>Usuário:</label>
                            <p class="form-control-static"><?= htmlspecialchars($row['nome_usu']) ?></p>
                        </div>

                        <div class="form-group">
                            <label>Prev. Entrega:</label>
                            <p class="form-control-static"><?= htmlspecialchars($row['prev_entrega']) ?></p>
                        </div>

                        <div class="form-group">
                            <label>Preço da Compra:</label>
                            <p class="form-control-static"><?= htmlspecialchars($row['preco_compra']) ?></p>
                        </div>

                        <div class="form-group">
                            <label for="data_entrega_efetiva">Data de Entrega Efetiva:</label>
                            <input type="datetime-local" id="data_entrega_efetiva" name="data_entrega_efetiva" class="form-control" required value="<?= htmlspecialchars($row['data_entrega_efetiva']) ?>">
                        </div>

                        <button type="submit" class="btn btn-success">Atualizar Data de Entrega</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

   
</body>
</html>
