<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inserir Item na Ordem de Serviço
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_ordem"], $_POST["id_serv"], $_POST["preco_items_os"])) {
    // Verifica se algum campo obrigatório está vazio
    if (empty($_POST["id_ordem"]) || empty($_POST["id_serv"]) || empty($_POST["preco_items_os"])) {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        // Captura dados do formulário
        $id_ordem = $_POST["id_ordem"];
        $id_serv = $_POST["id_serv"];
        $preco_items_os = $_POST["preco_items_os"];
        
        // Preparar a inserção
        $stmt = $conn->prepare("INSERT INTO Items_os (id_ordem, id_serv, preco_items_os) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $id_ordem, $id_serv, $preco_items_os);

        // Executar a inserção
        if ($stmt->execute()) {
            $success = "Item cadastrado com sucesso.";
        } else {
            $aviso = "Erro ao cadastrar item: " . $stmt->error;
        }
    }
}

// Consulta para buscar itens da ordem de serviço
$sql = "SELECT ios.*, s.nome_serv 
        FROM Items_os ios 
        JOIN Servico s ON ios.id_serv = s.id_serv";
$itens_os = $conn->query($sql);

// Verificar se houve erro na consulta
if (!$itens_os) {
    die("Erro na consulta: " . $conn->error);
}

// Consulta para buscar serviços
$servicos = $conn->query("SELECT id_serv, nome_serv FROM Servico");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens da Ordem de Serviço | Agro Malandrin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box">
                    <br><h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Itens da Ordem de Serviço</h3><br>

                    <!-- Exibir mensagens de aviso ou sucesso -->
                    <?php if (!empty($aviso)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <!-- Formulário de Inserção de Itens da Ordem de Serviço -->
                    <form action="itens_os.php" method="POST">
                        <div class="form-group">
                            <label for="id_ordem">ID da Ordem:</label>
                            <input type="number" id="id_ordem" name="id_ordem" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="id_serv">Serviço:</label>
                            <select name="id_serv" id="id_serv" class="form-control" required>
                                <option value="">Selecione</option>
                                <?php while ($servico = $servicos->fetch_assoc()): ?>
                                    <option value="<?= $servico['id_serv'] ?>"><?= htmlspecialchars($servico['nome_serv']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="preco_items_os">Preço dos Itens:</label>
                            <input type="number" step="0.01" min="0" id="preco_items_os" name="preco_items_os" required class="form-control">
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
                    <h3 style="margin-left: 18%;">Tabela de Itens da Ordem de Serviço</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered" style="width: 63%; margin: auto;">
                            <thead>
                                <tr class="table-success">
                                    <th style="width: 150px;">ID Ordem</th>
                                    <th style="width: 110px;">ID Serviço</th>
                                    <th style="width: 190px;">Nome do Serviço</th>
                                    <th style="width: 150px;">Preço dos Itens</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $itens_os->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id_ordem']) ?></td>
                                        <td><?= htmlspecialchars($row['id_serv']) ?></td>
                                        <td><?= htmlspecialchars($row['nome_serv']) ?></td>
                                        <td><?= number_format($row['preco_items_os'], 2, ',', '.') ?></td>
                                        <td>
                                            <a href="edit/edit_items_os.php?id=<?= $row['id_ordem'] ?>" class="btn btn-primary">Edit</a>
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
