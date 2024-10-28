<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Inserir Compra
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome_serv"], $_POST["preco_serv"], $_POST["prazo_serv"])) {
    // Verifica se algum campo obrigatório está vazio
    if (empty($_POST["nome_serv"]) || empty($_POST["preco_serv"]) || empty($_POST["prazo_serv"])) {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        // Captura dados do formulário
        $nome_serv = $_POST["nome_serv"];
        $preco_serv = $_POST["preco_serv"];
        $prazo_serv = $_POST["prazo_serv"];
        $desc_serv = $_POST["desc_serv"];
        $status_serv = "ativo";

        // Preparar a inserção
        $stmt = $conn->prepare("INSERT INTO Servico (nome_serv, desc_serv, preco_serv, prazo_serv, status_serv) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $nome_serv, $desc_serv, $preco_serv, $prazo_serv, $status_serv);

        // Executar a inserção
        if ($stmt->execute()) {
            $success = "Serviço cadastrado com sucesso.";
        } else {
            $aviso = "Erro ao cadastrar o serviço: " . $stmt->error;
        }
    }
}

$ativo = isset($_GET['ativo']) ? $_GET['ativo'] : 'ativo'; // Default para 'ativo'

// Verifica se a variável 'ativo' foi passada via GET
$sql = ($ativo === 'desabilitado') ? "SELECT * FROM Servico WHERE status_serv = 'desabilitado'" : "SELECT * FROM Servico WHERE status_serv = 'ativo'";


$servicos = $conn->query($sql);


// Desabilitar Usuário
if (isset($_GET["id_serv"]) && is_numeric($_GET["id_serv"]) && isset($_GET["del"])) {
    $id_serv = (int) $_GET["id_serv"];
    $stmt = $conn->prepare("UPDATE Servico SET status_serv = 'desabilitado' WHERE id_serv = ?");
    $stmt->bind_param('i', $id_serv);
    
    if ($stmt->execute()) {
        $success = "Usuário desabilitado com sucesso.";
    } else {
        $aviso = "Erro ao desabilitar usuário: " . $stmt->error;
    }
}

if (isset($_GET['reabilitar']) && is_numeric($_GET['reabilitar'])) {
    $id_serv = (int)$_GET['reabilitar'];
    $stmt = $conn->prepare("UPDATE Servico SET status_serv = 'ativo' WHERE id_serv = ?");
    $stmt->bind_param('i', $id_serv);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'> Usuário Reabilitado com sucesso.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao reabilitar usuário.</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servico | Agro Malandrin</title>
</head>

    <?php
        include_once 'includes/header.php';
        require_once 'includes/dbconnect.php';
    ?>
<body>
    <div class="container justify-content-center">
        <div class="row"> 
            <div class="col-md-6 mx-auto"> 
                <div class="box"> 
                    <br><h3>Cadastro de Serviços</h3><br>

                    <!-- Inicio do Formulario de Serviços -->
                    <form action="servicos.php" method="POST">
                    <input type="hidden" name="id_serv" value="">
 
                            <label for="nome_serv">Nome do Serviço:</label>
                            <input type="text" id="nome_serv" name="nome_serv" required class="form-control"><br>
 
 
                            <label for="desc_serv">Descrição do Serviço:</label>
                            <textarea type="text" id="desc_serv" name="desc_serv" required class="form-control"></textarea><br>
 
                            
                            <label for="preco_serv">Preço do Serviço:</label>
                            <input type="number" step="0.01" min="0" id="preco_serv" name="preco_serv" required class="form-control"><br>

                            <label for="prazo_serv">Previsão de Entrega:</label>
                            <input type="datetime-local" id="prazo_serv" name="prazo_serv" required class="form-control"><br>
                            
                            <button type="submit"  class="btn btn-success" >Cadastrar</button>
                        </form><br><br>

                         <!-- Fim do Formulario de Serviços -->
                    </div>
                </div>
            </div>
            <hr>

            <br><br>
 
            <div class="container">
                <div class="row">
                    <div class="col-md-3 mx-auto">
                        <div class="box">
                                    
                            <a href="servicos.php?ativo=<?= $ativo === 'desabilitado' ? 'ativo' : 'desabilitado' ?>" 
                                class="btn <?= $ativo === 'desabilitado' ? 'btn btn-primary' : 'btn btn-danger' ?>">
                                Ver Serviços <?= $ativo === 'desabilitado' ? 'Ativos' : 'Desabilitados' ?>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
           
            <div class="container">
                <div class="row">
                    <div class="box">
                        <h3 style="margin-left: 5px;">Servicos <?= $ativo === "desabilitado" ? "Desativados" : "Ativos" ?></h3>
                        <div class="table-responsive">

                            <!-- Inicio da Tabela de Serviços -->
                            <table  class="table table-bordered" style="width: 99%; margin: auto;">
                                <thead>
                                    <tr class="table-success">
                                        <th style="width: 2%;">ID</th>
                                        <th style="width: 170px;">Nome do Serviço</th>
                                        <th style="width: 280px;">Descrição do Serviço</th>
                                        <th style="width: 110px;">Preço do Serviço</th>
                                        <th style="width: 130px;">Previsão de Entrega</th> 
                                        <th style="width: 60px;">Status</th>
                                        <th style="width: 140px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php while ($row = $servicos->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id_serv'] ?></td>
                                        <td><?= $row['nome_serv'] ?></td>
                                        <td><?= $row['desc_serv'] ?></td>
                                        <td><?= $row['preco_serv'] ?></td>
                                        <td><?= $row['prazo_serv'] ?></td>
                                        <td><?= $row['status_serv'] ?></td>
                                        <td>
                                        <?php echo "<a href='edit/edit_serv.php?id=".$row['id_serv']."' class='btn btn-primary'>Edit </a> |"; ?>
                                            
                                            <?php if ($ativo === 'desabilitado'): ?>
                                                <a href="?reabilitar=<?= $row['id_serv'] ?>" class="btn btn-success">Reabilitar</a>
                                            <?php else: ?>
                                                <a href="servicos.php?id_serv=<?= $row['id_serv'] ?>&del=1" onclick="return confirm('Você tem certeza que deseja desligar este serviço ?');" class="btn btn-danger">Desligar</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>

                            <!-- Fim da Tabela de Serviços -->
                        </div>
                    </div>
                
                </div>
            </div>
 
</body>
</html>