<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inserir Compra
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_for"], $_POST["id_usu"], $_POST["prev_entrega"], $_POST["data_entrega_efetiva"], $_POST["preco_compra"])) {
    // Verifica se algum campo obrigatório está vazio
    if (empty($_POST["id_for"]) || empty($_POST["id_usu"]) || empty($_POST["prev_entrega"]) || empty($_POST["data_entrega_efetiva"]) || empty($_POST["preco_compra"])) {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        // Captura dados do formulário
        $id_for = $_POST["id_for"];
        $id_usu = $_POST["id_usu"];
        $prev_entrega = $_POST["prev_entrega"];
        $data_entrega_efetiva = $_POST["data_entrega_efetiva"]; // Nova variável para data de entrega efetiva
        $data_compra = date('Y-m-d H:i:s'); // Data atual
        $preco_compra = $_POST["preco_compra"];
        
        // Preparar a inserção
        $stmt = $conn->prepare("INSERT INTO Compra (data_compra, id_for, id_usu, prev_entrega, data_entrega_efetiva, preco_compra) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siissi", $data_compra, $id_for, $id_usu, $prev_entrega, $data_entrega_efetiva, $preco_compra);

        // Executar a inserção
        if ($stmt->execute()) {
            $success = "Compra cadastrada com sucesso.";
        } else {
            $aviso = "Erro ao cadastrar a compra: " . $stmt->error;
        }
    }
}

// Consulta para buscar compras
$sql = "SELECT c.*, u.nome_usu, f.nome_for FROM Compra c 
        JOIN Usuario u ON c.id_usu = u.id_usu 
        JOIN Fornecedor f ON c.id_for = f.id_for";
$compras = $conn->query($sql);

// Verificar se houve erro na consulta
if (!$compras) {
    die("Erro na consulta: " . $conn->error);
}

// Consulta para buscar fornecedores e usuários
$fornecedores = $conn->query("SELECT id_for, nome_for FROM Fornecedor");
$usuarios = $conn->query("SELECT id_usu, nome_usu FROM Usuario");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras | Agro Malandrin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box">

                <div class="d-flex justify-content-end mb-3 fixed-top">
                            <a href="#formulario" class="btn btn-outline-primary me-2">Ir para Formulário</a>
                            <a href="#tabela" class="btn btn-outline-secondary">Ir para Tabela</a>
                        </div>


                    <br><h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Compras</h3><br>

                    <!-- Exibir mensagens de aviso ou sucesso -->
                    <?php if (!empty($aviso)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <!-- Formulário de Inserção de Compras -->
                    <form action="compras.php" method="POST" id="formulario">
                        <div class="form-group">
                            <label for="id_for">Fornecedor:</label>
                            <select name="id_for" id="id_for" class="form-control" required>
                                <option value="">Selecione</option>
                                <?php while ($fornecedor = $fornecedores->fetch_assoc()): ?>
                                    <option value="<?= $fornecedor['id_for'] ?>"><?= htmlspecialchars($fornecedor['nome_for']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_usu">Usuário:</label>
                            <select name="id_usu" id="id_usu" class="form-control" required>
                                <option value="">Selecione</option>
                                <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                                    <option value="<?= $usuario['id_usu'] ?>"><?= htmlspecialchars($usuario['nome_usu']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="prev_entrega">Previsão de Entrega:</label>
                            <input type="datetime-local" id="prev_entrega" name="prev_entrega" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="data_entrega_efetiva">Data de Entrega Efetiva:</label>
                            <input type="datetime-local" id="data_entrega_efetiva" name="data_entrega_efetiva" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="preco_compra">Preço da Compra:</label>
                            <input type="number" step="0.01" min="0" id="preco_compra" name="preco_compra" required class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success">Cadastrar Compra</button>
                    </form><br>
                </div>
            </div>
        </div>
        <hr>

        <br>

        <div class="container">          
            <div class="row">
                <div class="box">
                    <h3>Tabela de Compras</h3>
                    <div class="table-responsive">

                        
                        <table class="table table-bordered" id="tabela">
                            <thead>
                                <tr class="table-success">
                                    <th style="width: 3%;">ID</th>
                                    <th>Data da Compra</th>
                                    <th>Fornecedor</th>
                                    <th style="width: 120px;">Usuário</th>
                                    <th>Prev. Entrega</th>
                                    <th>Data Entrega Efetiva</th>
                                    <th>Preço da Compra</th>
                                    <th style="width: 70px;">Ações</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $compras->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id_compra']) ?></td>
                                        <td><?= htmlspecialchars($row['data_compra']) ?></td>
                                        <td><?= htmlspecialchars($row['nome_for']) ?></td>
                                        <td><?= htmlspecialchars($row['nome_usu']) ?></td>
                                        <td><?= htmlspecialchars($row['prev_entrega']) ?></td>
                                        <td><?= htmlspecialchars($row['data_entrega_efetiva']) ?></td>
                                        <td><?= htmlspecialchars($row['preco_compra']) ?></td>
                                        <td>
                                            <a href="edit/edit_compra.php?id=<?= $row['id_compra'] ?>" class="btn btn-primary">Edit</a>
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
