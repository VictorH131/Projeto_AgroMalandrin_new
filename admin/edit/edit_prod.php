<?php
require_once '../includes/dbconnect.php';

$success = '';
$aviso = '';

// Verifica se um ID de produto foi passado na URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_prod = (int)$_GET['id'];

    // Busca o produto no banco de dados
    $stmt = $conn->prepare("SELECT * FROM Produto WHERE id_prod = ?");
    $stmt->bind_param('i', $id_prod);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o produto foi encontrado
    if ($result->num_rows === 1) {
        $produto = $result->fetch_assoc();
    } else {
        $aviso = "Produto não encontrado.";
    }
} else {
    $aviso = "ID do produto inválido.";
}

// Atualiza o produto no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura dados do formulário
    $nome_prod = $_POST["nome_prod"];
    $desc_prod = $_POST["desc_prod"];
    $marca = $_POST["marca"];
    $preco_venda = $_POST["preco_venda"];
    $estoque_minimo = $_POST["estoque_minimo"];
    $status_prod = $_POST["status_prod"];

    // Verifica se algum campo obrigatório está vazio
    if (empty($nome_prod) || empty($desc_prod) || empty($marca) || empty($preco_venda) || empty($estoque_minimo) || empty($status_prod)) {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        // Preparar a atualização
        $stmt = $conn->prepare("UPDATE Produto SET nome_prod = ?, desc_prod = ?, marca = ?, preco_venda = ?, estoque_minimo = ?, status_prod = ? WHERE id_prod = ?");
        $stmt->bind_param("ssssssi", $nome_prod, $desc_prod, $marca, $preco_venda, $estoque_minimo, $status_prod, $id_prod);

        // Executar a atualização
        if ($stmt->execute()) {
            $success = "Produto atualizado com sucesso.";
        } else {
            $aviso = "Erro ao atualizar produto: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar - Produto | Agro Malandrin</title>
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
                        <a href="../produtos.php" class="btn btn-secondary">Voltar</a>
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
                    <br><h3><i class="glyphicon glyphicon-pencil"></i>&nbsp;Editar Produto</h3><br>
                    
                    <!-- Formulário de Edição de Produto -->
                    <form action="edit_prod.php?id=<?= $id_prod ?>" method="POST">
                        <label for="nome_prod">Nome:</label>
                        <input type="text" id="nome_prod" name="nome_prod" value="<?= htmlspecialchars($produto['nome_prod']) ?>" required class="form-control"><br>

                        <label for="desc_prod">Descrição:</label>
                        <textarea id="desc_prod" name="desc_prod" required class="form-control"><?= htmlspecialchars($produto['desc_prod']) ?></textarea><br>

                        <label for="marca">Marca:</label>
                        <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($produto['marca']) ?>" required class="form-control"><br>

                        <label for="preco_venda">Preço de Venda:</label>
                        <input type="text" id="preco_venda" name="preco_venda" value="<?= htmlspecialchars($produto['preco_venda']) ?>" required class="form-control"><br>

                        <label for="estoque_minimo">Estoque Mínimo:</label>
                        <input type="number" id="estoque_minimo" name="estoque_minimo" value="<?= htmlspecialchars($produto['estoque_minimo']) ?>" required class="form-control"><br>

                        <label for="status_prod">Status:</label>
                        <select name="status_prod" id="status_prod" class="form-control" required>
                            <option value="ativo" <?= $produto['status_prod'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                            <option value="desabilitado" <?= $produto['status_prod'] === 'desabilitado' ? 'selected' : '' ?>>Desabilitado</option>
                        </select><br>

                        <button type="submit" name="update" class="btn btn-success">Atualizar Compra</button>
                    </form>
                    <br>
                    <!-- Fim do Formulário de Edição de Produto -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
