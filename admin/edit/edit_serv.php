<?php
require_once '../includes/dbconnect.php';

$success = '';
$aviso = '';

// Verifica se um ID de produto foi passado na URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_serv = (int)$_GET['id'];

    // Busca o produto no banco de dados
    $stmt = $conn->prepare("SELECT * FROM Servico WHERE id_serv = ?");
    $stmt->bind_param('i', $id_serv);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o produto foi encontrado
    if ($result->num_rows === 1) {
        $servicos = $result->fetch_assoc();
    } else {
        $aviso = "Produto não encontrado.";
    }
} else {
    $aviso = "ID do produto inválido.";
}

// Atualiza o produto no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura dados do formulário
    $nome_serv = $_POST["nome_serv"];
    $desc_serv = $_POST["desc_serv"];
    $preco_serv = $_POST["preco_serv"];
    $prazo_serv = $_POST["prazo_serv"];

    // Verifica se algum campo obrigatório está vazio
    if (empty($nome_serv) || empty($desc_serv) || empty($preco_serv) || empty($prazo_serv)) {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        // Preparar a atualização
        $stmt = $conn->prepare("UPDATE Servico SET nome_serv = ?, desc_serv = ?, preco_serv = ?, prazo_serv = ? WHERE id_serv = ?");
        $stmt->bind_param("ssssi", $nome_serv, $desc_serv, $preco_serv, $prazo_serv, $id_serv);

        // Executar a atualização
        if ($stmt->execute()) {
            $success = "Serviço atualizado com sucesso.";
        } else {
            $aviso = "Erro ao atualizar o serviço: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar - Serviço | Agro Malandrin</title>
    <!-- colocando o icone da pagina-->
    <link rel="shortcut icon" href="../../multimidia/icones/planta.png" type="image/x-icon">


    <!-- linkando ao Bootstrap v5.1 -->
        <!--CSS-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

        <div class="container">
            <div class="row">
                <div class="col-md-9 mx-auto">
                    <div class="box">
                        <a href="../servicos.php" class="btn btn-secondary">Voltar</a>
                    </div>
                </div>
            </div>
        </div>





    <div class="container">
        <!-- Exibir mensagens de aviso ou sucesso -->
        <?php if (!empty($aviso)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box">
                    <br><h3><i class="glyphicon glyphicon-pencil"></i>&nbsp;Editar Serviço</h3><br>
                    
                    <!-- Formulário de Edição de Serviço -->
                    <form action="edit_serv.php?id=<?= $id_serv ?>" method="POST">
                        <label for="nome_serv">Nome do Serviço:</label>
                        <input type="text" id="nome_serv" name="nome_serv" value="<?= htmlspecialchars($servicos['nome_serv']) ?>" required class="form-control"><br>

                        <label for="desc_serv">Descrição do Serviço:</label>
                        <input type="text" id="desc_serv" name="desc_serv" value="<?= htmlspecialchars($servicos['desc_serv']) ?>" required class="form-control"><br>

                        <label for="preco_serv">Preço do Serviço:</label>
                        <input type="number" id="preco_serv" name="preco_serv" value="<?= htmlspecialchars($servicos['preco_serv']) ?>" required class="form-control"><br>

                        <label for="prazo_serv">Previsão de Entrega:</label>
                        <input type="datetime-local" id="prazo_serv" name="prazo_serv" value="<?= htmlspecialchars($servicos['prazo_serv']) ?>" required class="form-control"><br>

                        <button type="submit" name="update" class="btn btn-success">Atualizar Pedido</button>
                    </form>
                    <br>
                    <!-- Fim do Formulário de Edição de Pedido -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
