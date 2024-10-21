<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Consulta para buscar itens do pedido
$sql = "SELECT ip.id_ped, ip.id_prod, ip.preco_vendido, p.nome_prod, ped.data_ped 
        FROM items_pedido ip 
        JOIN Produto p ON ip.id_prod = p.id_prod 
        JOIN Pedido ped ON ip.id_ped = ped.id_ped";
$itens_pedido = $conn->query($sql);

// Verificar se houve erro na consulta
if (!$itens_pedido) {
    die("Erro na consulta: " . $conn->error);
}

// Consulta para buscar produtos
$produtos = $conn->query("SELECT id_prod, nome_prod FROM Produto");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens do Pedido | Agro Malandrin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box">
                    <br><h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Itens do Pedido</h3><br>

                    <!-- Exibir mensagens de aviso ou sucesso -->
                    <?php if (!empty($aviso)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <!-- Formulário de Inserção de Itens do Pedido -->
                    <form action="itens_pedido.php" method="POST">
                        <div class="form-group">
                            <label for="id_ped">Pedido:</label>
                            <select name="id_ped" id="id_ped" class="form-control" required>
                                <option value="">Selecione</option>
                                <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                                    <option value="<?= $pedido['id_ped'] ?>"><?= htmlspecialchars($pedido['data_ped']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_prod">Produto:</label>
                            <select name="id_prod" id="id_prod" class="form-control" required>
                                <option value="">Selecione</option>
                                <?php while ($produto = $produtos->fetch_assoc()): ?>
                                    <option value="<?= $produto['id_prod'] ?>"><?= htmlspecialchars($produto['nome_prod']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="preco_vendido">Preço Vendido:</label>
                            <input type="number" step="0.01" min="0" id="preco_vendido" name="preco_vendido" required class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success">Cadastrar Item</button>
                    </form><br>
                </div>
            </div>
        </div>
        <hr>

        <div class="container">          
            <div class="row">
                <div class="box">
                    <h3>Tabela de Itens do Pedido</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-success">
                                    <th>Data do Pedido</th>
                                    <th>ID Produto</th>
                                    <th>Nome do Produto</th>
                                    <th>Preço Vendido</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $itens_pedido->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['data_ped']) ?></td>
                                        <td><?= htmlspecialchars($row['id_prod']) ?></td>
                                        <td><?= htmlspecialchars($row['nome_prod']) ?></td>
                                        <td><?= number_format($row['preco_vendido'], 2, ',', '.') ?></td>
                                        <td>
                                            <a href="edit/edit_items_pedido.php?id=<?= $row['id_ped'] ?>" class="btn btn-primary">Edit</a>
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
