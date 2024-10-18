<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';
 
// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);
$success = '';
$aviso = '';
 
// Inserir/Atualizar Usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_for"], $_POST["id_usu"], $_POST["prev_entrega"], $_POST["preco_compra"], $_POST["data_entrega_efetiva"])) {
        // Verifica se algum campo obrigatório está vazio
        if (empty($_POST["id_for"]) || empty($_POST["id_usu"]) || empty($_POST["prev_entrega"]) || empty($_POST["preco_compra"]) || empty($_POST["data_entrega_efetiva"])) {
            $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Captura dados do formulário
            $id_for = $_POST["id_for"];
            $id_usu = $_POST["id_usu"];
            $prev_entrega = $_POST["prev_entrega"];
            $preco_compra = $_POST["preco_compra"];
            $data_entrega_efetiva = $_POST["data_entrega_efetiva"] ?? null; // Data de Entrega Efetiva pode ser nulo
   
                // Executar a inserção
                if ($stmt->execute()) {
                    $success = "Compra cadastrado com sucesso.";
                } else {
                    $aviso = "Erro ao cadastrar a compra: " . $stmt->error;
                }
            }
        }
    } else {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    }
 
// Desabilitar Usuário
if (isset($_GET["id_usu"]) && is_numeric($_GET["id_usu"]) && isset($_GET["del"])) {
    $id_usu = (int) $_GET["id_usu"];
    $stmt = $conn->prepare("UPDATE Usuario SET status_usu = 'desabilitado' WHERE id_usu = ?");
    $stmt->bind_param('i', $id_usu);
    if ($stmt->execute()) {
        $success = "Usuário desabilitado com sucesso.";
    } else {
        $aviso = "Erro ao desabilitar usuário: " . $stmt->error;
    }
}
 
// Consulta para buscar todos os usuários ativos/desativos
$sql = "SELECT * FROM Usuario WHERE status_usu = 'ativo'";
$usuarios = $conn->query($sql);
 
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras | Agro Malandrin</title>
</head>
 
<body>
    <div class="container">
        <!-- Exibir mensagens de aviso ou sucesso -->
       
        <?php if (!empty($aviso)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
        <?php endif; ?>
 
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
 
 
 
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="box">
                        <br><h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Compras</h3><br>

                        <!-- Inicio do Formulario de Compras -->
                        <form action="compra.php" method="POST">
                            <input type="hidden" name="id_compra" value="<?= isset($_POST['id_compra']) ? (int) $_POST['id_compra'] : -1 ?>">

                            <input type="hidden" name="data_compra" value="<?= date('Y-m-d H:i:s') ?>">
 
                            <label for="id_for">Fornecedor:</label><br>
                            <select name="id_for" required class="form-control">
                                <option value="">Selecione</option>
                                <?php
                                    $conn = new mysqli("localhost", "username", "password", "dbname");
                                    
                                    if ($conn->connect_error) {
                                        die("Falha na conexão: " . $conn->connect_error);
                                    }
                                    
                                    $stmt = $conn->prepare("SELECT id_for, nome_for FROM Fornecedor");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    
                                    while ($fornecedor = $result->fetch_assoc()) {
                                        $selected = (isset($_POST['id_for']) && $_POST['id_for'] == $fornecedor['id_for']) ? 'selected' : '';
                                        echo "<option value='{$fornecedor['id_for']}' $selected>{$fornecedor['nome_for']}</option>";
                                    }
                                    
                                    $stmt->close();
                                    $conn->close();                                    
                                ?>
                            </select>
 
                            <label for="id_usu">Usuário:</label>
                            <input type="text" name="id_usu" value="" disabled class="form-control"><br>
 
                            <label for="prev_entrega">Previsão de Entrega:</label>
                            <input type="date" name="prev_entrega" value="" required class="form-control"><br>
 
                            <label for="preco_compra">Preço de Compra:</label>
                            <input type="text" id="preco_compra" name="preco_compra" placeholder="R$ 0,00" value="" required class="form-control"><br>

                            <label for="data_entrega_efetiva">Data de Entrega Efetiva:</label>
                            <input type="date" name="data_entrega_efetiva" value="" class="form-control"><br>
 
                            <button type="submit" class="btn btn-success">Cadastrar</button>
                        </form><br><br>

                        <!-- Fim do Formulario de Compras -->
                    </div>
                </div>
            </div>
 
           
           
            <div class="container">
                <div class="row">
                    <div class="box">
                        <h2>Tabela de Compras</h2>
                        <div class="table-responsive">

                        <!-- Inicio da Tabela de Compras -->
                            <table  class="table table-bordered ">
                                <thead>
                                    <tr class="table-success">
                                        <th>ID Compra</th>
                                        <th>Data Compra</th>
                                        <th>Fornecedor</th>
                                        <th>Usuário</th>
                                        <th>Previsão de Entrega</th> 
                                        <th>Data Entrega Efetiva</th>
                                        <th>Preço</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $compras->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['id_compra'] ?></td>
                                            <td><?= $row['data_compra'] ?></td>
                                            <td><?= $row['id_for'] ?></td>
                                            <td><?= $row['id_usu'] ?></td>
                                            <td><?= $row['prev_entrega'] ?></td>
                                            <td><?= $row['data_entrega_efetiva'] ?></td>
                                            <td><?= $row['preco_compra'] ?></td>
                                            <td id="acao">                    
                                                <a href="compra.php?id_compra=<?= $row['id_compra'] ?>" class="btn btn-primary">Editar</a> |
                                                <a href="compra.php?id_compra=<?= $row['id_compra'] ?>&del=1" onclick="return confirm('Tem certeza que deseja desabilitar este serviço?');" class="btn btn-danger">Desabilitar</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>

                            <!-- Fim da Tabela de Compras -->
                        </div>
                    </div>
                </div>
            </div>
</body>
</html>