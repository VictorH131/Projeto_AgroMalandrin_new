<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);
$success = '';
$aviso = '';

// Inserir/Atualizar Produto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nome_prod"], $_POST["desc_prod"], $_POST["marca"], $_POST["preco_venda"], $_POST["estoque_minimo"], $_POST["status_prod"])) {
        // Verifica se algum campo obrigatório está vazio
        if (empty($_POST["nome_prod"]) || empty($_POST["desc_prod"]) || empty($_POST["marca"]) || empty($_POST["preco_venda"]) || empty($_POST["estoque_minimo"]) || empty($_POST["status_prod"])) {
            $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Captura dados do formulário
            $nome_prod = $_POST["nome_prod"];
            $desc_prod = $_POST["desc_prod"];
            $marca = $_POST["marca"];
            $preco_venda = $_POST["preco_venda"];
            $estoque_minimo = $_POST["estoque_minimo"];
            $status_prod = "ativo";
            $data_cadastro_prod = date('Y-m-d H:i:s'); // Setando a data e hora atual

            // Preparar a inserção
            $stmt = $conn->prepare("INSERT INTO Produto (nome_prod, desc_prod, marca, preco_venda, estoque_minimo, status_prod, data_cadastro_prod) VALUES (?, ?, ?, ?, ?, ?, ?)");

            // Bind de todos os parâmetros
            $stmt->bind_param("sssssss", 
                $nome_prod, 
                $desc_prod, 
                $marca, 
                $preco_venda, 
                $estoque_minimo, 
                $status_prod, 
                $data_cadastro_prod
            );

            // Executar a inserção
            if ($stmt->execute()) {
                $success = "Produto cadastrado com sucesso.";
            } else {
                $aviso = "Erro ao cadastrar produto: " . $stmt->error;
            }
        }
    } else {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    }
}

// Desabilitar Produto
if (isset($_GET["id_prod"]) && is_numeric($_GET["id_prod"]) && isset($_GET["del"])) {
    $id_prod = (int) $_GET["id_prod"];
    $stmt = $conn->prepare("UPDATE Produto SET status_prod = 'desabilitado' WHERE id_prod = ?");
    $stmt->bind_param('i', $id_prod);
    
    if ($stmt->execute()) {
        $success = "Produto desabilitado com sucesso.";
    } else {
        $aviso = "Erro ao desabilitar produto: " . $stmt->error;
    }
}

// Verifica se a variável 'status' foi passada via GET
$status = isset($_GET['status']) ? $_GET['status'] : 'ativo'; // Default para 'ativo'

// Consulta para buscar produtos com base no status
$sql = ($status === 'desabilitado') ? "SELECT * FROM Produto WHERE status_prod = 'desabilitado'" : "SELECT * FROM Produto WHERE status_prod = 'ativo'";
$produtos = $conn->query($sql);

// Reabilitar Produto
if (isset($_GET['reabilitar']) && is_numeric($_GET['reabilitar'])) {
    $id_prod = (int)$_GET['reabilitar'];
    $stmt = $conn->prepare("UPDATE Produto SET status_prod = 'ativo' WHERE id_prod = ?");
    $stmt->bind_param('i', $id_prod);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'> Produto reabilitado com sucesso.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao reabilitar produto.</div>";
    }
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Agro Malandrin</title>
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
                        <br><h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Produto</h3><br>
                        
                        <!-- Inicio do Formulario de Produtos -->
                        <form action="produtos.php" method="POST">
                            <input type="hidden" name="id_prod" value="<?= isset($_POST['id_prod']) ? (int) $_POST['id_prod'] : -1 ?>">
 
                            <label for="nome_prod">Nome:</label>
                            <input type="text" id="nome_prod" name="nome_prod" required class="form-control"><br>
 
                            <label for="desc_prod">Descrição:</label>
                            <textarea id="desc_prod" name="desc_prod" required class="form-control"></textarea><br>
 
                            <label for="marca">Marca:</label>
                            <input type="text" id="marca" name="marca" required class="form-control"><br>
 
                            <label for="preco_venda">Preço de Venda:</label>
                            <input type="text" id="preco_venda" name="preco_venda" required class="form-control"><br>
 
                            <label for="estoque_minimo">Estoque Mínimo:</label>
                            <input type="number" id="estoque_minimo" name="estoque_minimo" required class="form-control"><br>
 
                            
 
                            <button type="submit" class="btn btn-success">Cadastrar</button>
                        </form><br><br>

                        <!-- Fim do Formulario de Produtos -->
                    </div>
                </div>
            </div>
            <hr>
 
            <br><br>
            
            <div class="container">
                <div class="row">
                    <div class="col-md-3 mx-auto">
                        <div class="box">
                                    
                            <a href="Produtos.php?status=<?= $status === 'desabilitado' ? 'ativo' : 'desabilitado' ?>" 
                                class="btn <?= $status === 'desabilitado' ? 'btn btn-primary' : 'btn btn-danger' ?>">
                                Ver Produtos <?= $status === 'desabilitado' ? 'Ativos' : 'Desabilitados' ?>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
           
            <div class="container">
                <div class="row">
                    <div class="box">
                        <h3 style="margin-left: 90px;">Produtos <?= $status === "desabilitado" ? "Desativados" : "Ativos" ?></h3>
                        <div class="table-responsive">

                            <!-- Inicio da Tabela de Produtos -->
                            <table class="table table-bordered" style="width: 85%; margin: auto;">
                                <thead>
                                    <tr class="table-success">
                                        <th style="width: 2%;">ID</th>
                                        <th style="width: 150px;">Nome</th>
                                        <th style="width: 230px;">Descrição</th>
                                        <th style="width: 100px;">Marca</th>
                                        <th style="width: 140px;">Preço de Venda</th>
                                        <th style="width: 150px;">Estoque Mínimo</th>
                                        <th>Status</th>
                                        <th style="width: 185px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $produtos->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id_prod'] ?></td>
                                        <td><?= $row['nome_prod'] ?></td>
                                        <td><?= $row['desc_prod'] ?></td>
                                        <td><?= $row['marca'] ?></td>
                                        <td><?= $row['preco_venda'] ?></td>
                                        <td><?= $row['estoque_minimo'] ?></td>
                                        <td><?= $row['status_prod'] ?></td>
                                        <td>
                                            <?php echo "<a href='edit/edit_prod.php?id=".$row['id_prod']."' class='btn btn-primary'>Edit</a> |"; ?>
                                            
                                            <?php if ($status === 'desabilitado'): ?>
                                                <a href="?reabilitar=<?= $row['id_prod'] ?>" class="btn btn-success">Reabilitar</a>
                                            <?php else: ?>
                                                <a href="produtos.php?id_prod=<?= $row['id_prod'] ?>&del=1" class="btn btn-danger">Desabilitar</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <!-- Fim da Tabela de Produtos -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
