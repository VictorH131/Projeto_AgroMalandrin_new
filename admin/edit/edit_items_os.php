<?php
require_once '../includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se o ID do item foi passado na URL
if (!isset($_GET['id'])) {
    die("ID do item não especificado.");
}
 
$id_ordem = $_GET['id'];

// Consultar o item para editar
$stmt = $conn->prepare("SELECT ios.*, s.nome_serv FROM Items_os ios JOIN Servico s ON ios.id_serv = s.id_serv WHERE ios.id_ordem = ?");
$stmt->bind_param("i", $id_ordem);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Item não encontrado.");
}

$item = $result->fetch_assoc();

// Atualizar item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_serv = $_POST["id_serv"];
    $preco_items_os = $_POST["preco_items_os"];

    // Preparar a atualização
    $update_stmt = $conn->prepare("UPDATE Items_os SET preco_items_os = ? WHERE id_ordem = ?");
    $update_stmt->bind_param("di", $preco_items_os, $id_ordem);

    // Executar a atualização
    if ($update_stmt->execute()) {
        $success = "Item atualizado com sucesso.";
        header("Location: ../itens_os.php"); // Redirecionar após a atualização
        exit();
    } else {
        $aviso = "Erro ao atualizar item: " . $update_stmt->error;
    }
}

// Consulta para buscar serviços
$servicos = $conn->query("SELECT id_serv, nome_serv FROM Servico");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item da Ordem de Serviço | Agro Malandrin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- colocando o icone da pagina-->
    <link rel="shortcut icon" href="../../multimidia/icones/planta.png" type="image/x-icon">
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="box">
                <a href="../itens_os.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>
</div>

    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box">
                    <h3><i class="glyphicon glyphicon-edit"></i>&nbsp;Editar Item da Ordem de Serviço</h3>

                    <!-- Exibir mensagens de aviso ou sucesso -->
                    <?php if (!empty($aviso)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <!-- Formulário de Edição de Itens da Ordem de Serviço -->
                    <form action="edit_items_os.php?id=<?= $item['id_ordem'] ?>" method="POST">
                        <div class="form-group">
                            <label for="id_ordem">ID da Ordem:</label>
                            <input type="number" id="id_ordem" name="id_ordem" value="<?= htmlspecialchars($item['id_ordem']) ?>" readonly class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="id_serv">Serviço:</label>
                            <input type="text" id="id_serv" name="id_serv" value="<?= htmlspecialchars($item['nome_serv']) ?>" readonly class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="preco_items_os">Preço dos Itens:</label>
                            <input type="number" step="0.01" min="0" id="preco_items_os" name="preco_items_os" value="<?= htmlspecialchars($item['preco_items_os']) ?>" required class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success">Atualizar Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
