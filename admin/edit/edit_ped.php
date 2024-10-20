<?php

require_once '../includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o ID do pedido foi passado
if (!isset($_GET['id'])) {
    die("ID do pedido não fornecido.");
}

$id_ped = $_GET['id'];

// Consultar os dados do pedido
$stmt = $conn->prepare("SELECT p.*, u.nome_usu, c.nome_cli FROM Pedido p 
                        JOIN Usuario u ON p.id_usu = u.id_usu 
                        JOIN Cliente c ON p.id_cli = c.id_cli 
                        WHERE p.id_ped = ?");
$stmt->bind_param("i", $id_ped);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Pedido não encontrado.");
}

$row = $result->fetch_assoc();

// Atualizar dados do pedido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["data_entrega_ped"])) {
    $data_entrega_ped = $_POST["data_entrega_ped"];

    // Verifica se a data está no formato correto
    $data_entrega_ped = date('Y-m-d H:i:s', strtotime($data_entrega_ped)); // Formato compatível com banco de dados

    // Preparar a atualização
    $update_stmt = $conn->prepare("UPDATE Pedido SET data_entrega_ped = ? WHERE id_ped = ?");
    $update_stmt->bind_param("si", $data_entrega_ped, $id_ped);

    if ($update_stmt->execute()) {
        $success = "Data de entrega do pedido atualizada com sucesso.";
    } else {
        $aviso = "Erro ao atualizar a data de entrega do pedido: " . $update_stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar - Pedido | Agro Malandrin</title>

    <!-- Colocando o ícone da página -->
    <link rel="shortcut icon" href="../../multimidia/icones/planta.png" type="image/x-icon">

    <!-- Linkando ao Bootstrap v5.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="box">
                <a href="../pedidos.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="box">
                <br><h3>Editar Pedido</h3><br>

                <!-- Exibir mensagens de aviso ou sucesso -->
                <?php if (!empty($aviso)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <!-- Formulário de Edição de Pedidos -->
                <form action="edit_ped.php?id=<?= $id_ped ?>" method="POST">
                    <div class="form-group">
                        <label>ID do Pedido:</label>
                        <p class="form-control-static"><?= htmlspecialchars($row['id_ped']) ?></p>
                    </div>

                    <div class="form-group">
                        <label>Data do Pedido:</label>
                        <p class="form-control-static"><?= htmlspecialchars($row['data_ped']) ?></p>
                    </div>

                    <div class="form-group">
                        <label>Cliente:</label>
                        <p class="form-control-static"><?= htmlspecialchars($row['nome_cli']) ?></p>
                    </div>

                    <div class="form-group">
                        <label>Usuário:</label>
                        <p class="form-control-static"><?= htmlspecialchars($row['nome_usu']) ?></p>
                    </div>

                    <div class="form-group">
                        <label>Endereço de Entrega:</label>
                        <p class="form-control-static"><?= htmlspecialchars($row['endereco_entrega']) ?></p>
                    </div>

                    <div class="form-group">
                        <label for="data_entrega_ped">Data de Entrega:</label>
                        <input type="datetime-local" id="data_entrega_ped" name="data_entrega_ped" class="form-control" required value="<?= htmlspecialchars($row['data_entrega_ped']) ?>">
                    </div><br>

                    <button type="submit" class="btn btn-success">Atualizar Data de Entrega</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
