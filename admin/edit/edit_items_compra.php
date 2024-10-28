<?php
require_once '../includes/dbconnect.php'; // Verifique se este caminho está correto

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se o ID do item foi passado na URL
if (!isset($_GET['id'])) {
    die("ID do item não especificado.");
}

$id_compra = $_GET['id'];

// Consultar o item para editar
$stmt = $conn->prepare("SELECT ic.*, c.data_compra, p.nome_prod FROM Items_compra ic JOIN Compra c ON ic.id_compra = c.id_compra JOIN Produto p ON ic.id_prod = p.id_prod WHERE ic.id_compra = ?");
$stmt->bind_param("i", $id_compra);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Item não encontrado.");
}

$item = $result->fetch_assoc();

// Atualizar item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_prod = $item["id_prod"]; // Manter o ID do produto atual
    $preco_items_compra = $_POST["preco_items_compra"];

    // Preparar a atualização
    $update_stmt = $conn->prepare("UPDATE Items_compra SET preco_items_compra = ? WHERE id_compra = ? AND id_prod = ?");
    $update_stmt->bind_param("dii", $preco_items_compra, $id_compra, $id_prod);

    // Executar a atualização
    if ($update_stmt->execute()) {
        $success = "Item atualizado com sucesso.";
        header("Location: ../itens_compra.php"); // Ajuste o caminho aqui
        exit();
    } else {
        $aviso = "Erro ao atualizar item: " . $update_stmt->error;
    }
}

// Consulta para buscar produtos
$produtos = $conn->query("SELECT id_prod, nome_prod FROM Produto");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item da Compra | Agro Malandrin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- colocando o icone da pagina-->
    <link rel="shortcut icon" href="../../multimidia/icones/planta.png" type="image/x-icon">
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="box">
                <a href="../itens_compra.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>
</div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box">
                    <h3><i class="glyphicon glyphicon-edit"></i>&nbsp;Editar Item da Compra</h3>

                    <!-- Exibir mensagens de aviso ou sucesso -->
                    <?php if (!empty($aviso)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <!-- Formulário de Edição de Itens da Compra -->
                    <form action="edit_items_compra.php?id=<?= $item['id_compra'] ?>" method="POST">
                        <div class="form-group">
                            <label for="id_compra">Data da Compra:</label>
                            <input type="text" id="id_compra" name="id_compra" value="<?= htmlspecialchars($item['data_compra']) ?>" readonly class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="id_prod">Produto:</label>
                            <select name="id_prod" id="id_prod" class="form-control" required disabled>
                                <option value="<?= $item['id_prod'] ?>" selected><?= htmlspecialchars($item['nome_prod']) ?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="preco_compra">Preço da Compra:</label>
                            <input type="number" step="0.01" min="0" id="preco_compra" name="preco_compra" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="preco_items_compra">Preço dos Itens:</label>
                            <input type="number" step="0.01" min="0" id="preco_items_compra" name="preco_items_compra" value="<?= htmlspecialchars($item['preco_items_compra']) ?>" required class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success">Atualizar Item</button>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
