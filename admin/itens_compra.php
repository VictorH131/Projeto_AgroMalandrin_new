<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inserir Item na Compra
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_compra"], $_POST["id_prod"], $_POST["preco_items_compra"])) {
    // Verifica se algum campo obrigatório está vazio
    if (empty($_POST["id_compra"]) || empty($_POST["id_prod"]) || empty($_POST["preco_items_compra"])) {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        // Captura dados do formulário
        $id_compra = $_POST["id_compra"];
        $id_prod = $_POST["id_prod"];
        $preco_items_compra = $_POST["preco_items_compra"];
        
        // Preparar a inserção
        $stmt = $conn->prepare("INSERT INTO Items_compra (id_compra, id_prod, preco_items_compra) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $id_compra, $id_prod, $preco_items_compra);

        // Executar a inserção
        if ($stmt->execute()) {
            $success = "Item cadastrado com sucesso.";
        } else {
            $aviso = "Erro ao cadastrar item: " . $stmt->error;
        }
    }
}

// Consulta para buscar os itens da compra, incluindo a data da compra
$sql = "SELECT ic.*, p.nome_prod, c.data_compra 
        FROM Items_compra ic 
        JOIN Produto p ON ic.id_prod = p.id_prod 
        JOIN Compra c ON ic.id_compra = c.id_compra";
$itens_compra = $conn->query($sql);

// Verificar se houve erro na consulta
if (!$itens_compra) {
    die("Erro na consulta: " . $conn->error);
}

// Consulta para buscar compras
$compras = $conn->query("SELECT id_compra, data_compra FROM Compra");
$produtos = $conn->query("SELECT id_prod, nome_prod FROM Produto");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens da Compra | Agro Malandrin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box">
                    <br><h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Itens da Compra</h3><br>

                    <!-- Exibir mensagens de aviso ou sucesso -->
                    <?php if (!empty($aviso)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <!-- Formulário de Inserção de Itens da Compra -->
                    <form action="itens_compra.php" method="POST">
                        <div class="form-group">
                            <label for="id_compra">ID da Compra:</label>
                            <select name="id_compra" id="id_compra" class="form-control" required>
                                <option value="">Selecione uma Compra</option>
                                <?php while ($compra = $compras->fetch_assoc()): ?>
                                    <option value="<?= $compra['id_compra'] ?>"><?= htmlspecialchars($compra['data_compra']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_prod">Produto:</label>
                            <select name="id_prod" id="id_prod" class="form-control" required>
                                <option value="">Selecione um Produto</option>
                                <?php while ($produto = $produtos->fetch_assoc()): ?>
                                    <option value="<?= $produto['id_prod'] ?>"><?= htmlspecialchars($produto['nome_prod']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="preco_items_compra">Preço dos Itens:</label>
                            <input type="number" step="0.01" min="0" id="preco_items_compra" name="preco_items_compra" required class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success">Cadastrar Item</button>
                    </form><br>
                </div>
            </div>
        </div>
        <hr>

        <br>

        <div class="container">          
            <div class="row">
                <div class="box">
                    <h3 style="margin-left: 18%;">Tabela de Itens da Compra</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered" style="width: 63%; margin: auto;">
                            <thead>
                                <tr class="table-success">
                                    <th style="width: 150px;">Data da Compra</th>
                                    <th style="width: 110px;">ID Produto</th>
                                    <th style="width: 190px;">Nome do Produto</th>
                                    <th style="width: 150px;">Preço dos Itens</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $itens_compra->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['data_compra']))) ?></td>
                                        <td><?= htmlspecialchars($row['id_prod']) ?></td>
                                        <td><?= htmlspecialchars($row['nome_prod']) ?></td>
                                        <td><?= number_format($row['preco_items_compra'], 2, ',', '.') ?></td>
                                        <td>
                                            <a href="edit/edit_items_compra.php?id=<?= $row['id_compra'] ?>" class="btn btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
