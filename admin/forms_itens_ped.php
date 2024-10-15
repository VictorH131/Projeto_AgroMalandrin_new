<?php
    include_once '../secure/verifica_login/session.php';  // Verificando se você está logado
    include_once "../secure/includes/dbconnect.php";

    $erro = '';
    $success = '';

    // Inserir/Atualizar Item de Pedido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["id_ped"], $_POST["id_prod"], $_POST["preco_itens_ped"])) {
            if (empty($_POST["id_ped"]) || empty($_POST["id_prod"]) || empty($_POST["preco_itens_ped"])) {
                $erro = "Os campos Pedido, Produto e Preço são obrigatórios.";
            } else {
                $id_ped = $_POST["id_ped"];
                $id_prod = $_POST["id_prod"];
                $preco_itens_ped = $_POST["preco_itens_ped"];
                $id_itens_ped = isset($_POST["id_itens_ped"]) ? $_POST["id_itens_ped"] : null;

                if ($id_itens_ped === null) { // Inserir novo item de pedido
                    $stmt = $con->prepare("INSERT INTO Itens_Pedido (id_ped, id_prod, preco_itens_ped) VALUES (?, ?, ?)");
                    $stmt->bind_param("iis", $id_ped, $id_prod, $preco_itens_ped);

                    if ($stmt->execute()) {
                        $success = "Item de pedido registrado com sucesso.";
                    } else {
                        $erro = "Erro ao registrar item de pedido: " . $stmt->error;
                    }
                } else { // Atualizar item de pedido existente
                    $stmt = $con->prepare("UPDATE Itens_Pedido SET preco_itens_ped = ? WHERE id_ped = ? AND id_prod = ?");
                    $stmt->bind_param("dii", $preco_itens_ped, $id_ped, $id_prod);

                    if ($stmt->execute()) {
                        $success = "Item de pedido atualizado com sucesso.";
                    } else {
                        $erro = "Erro ao atualizar item de pedido: " . $stmt->error;
                    }
                }
            }
        } else {
            $erro = "Todos os campos são obrigatórios.";
        }
    }

    // Remover Item de Pedido
    if (isset($_GET["id_ped"]) && isset($_GET["id_prod"])) {
        $id_ped = (int) $_GET["id_ped"];
        $id_prod = (int) $_GET["id_prod"];

        $stmt = $con->prepare("DELETE FROM Itens_Pedido WHERE id_ped = ? AND id_prod = ?");
        $stmt->bind_param('ii', $id_ped, $id_prod);
        if ($stmt->execute()) {
            $success = "Item de pedido removido com sucesso.";
        } else {
            $erro = "Erro ao remover item de pedido: " . $stmt->error;
        }
    }

    // Listar Itens de Pedido
    $result = $con->query("SELECT ip.*, p.nome_prod, ped.data_ped FROM items_pedido ip LEFT JOIN Produto p ON ip.id_prod = p.id_prod LEFT JOIN Pedido ped ON ip.id_ped = ped.id_ped");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens Ped | Salão Novo Estilo</title>
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="styles/forms.css">
</head>
<?php 
    include_once "includes/nav.php"; 
?>
<body>
    <div class="container">
    <h1>Cadastro de Itens de Pedido</h1>

    <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

    <!-- Formulário para adicionar ou editar item de pedido -->
    <form action="forms_itens_ped.php" method="POST">
        <input type="hidden" name="id_itens_ped"
            value="<?= isset($_POST['id_itens_ped']) ? $_POST['id_itens_ped'] : '' ?>">

        <label for="id_ped">Pedido:</label><br>
        <select name="id_ped" required>
            <option value="">Selecione um pedido</option>
            <?php
            // Listar pedidos para o dropdown
            $pedidos = $con->query("SELECT id_ped, data_ped FROM Pedido");
            while ($pedido = $pedidos->fetch_assoc()) {
                $selected = (isset($_POST['id_ped']) && $_POST['id_ped'] == $pedido['id_ped']) ? 'selected' : '';
                echo "<option value='{$pedido['id_ped']}' $selected>{$pedido['data_ped']}</option>";
            }
            ?>
        </select><br><br>

        <label for="id_prod">Produto:</label><br>
        <select name="id_prod" required>
            <option value="">Selecione um produto</option>
            <?php
            // Listar produtos para o dropdown
            $produtos = $con->query("SELECT id_prod, nome_prod FROM Produto");
            while ($produto = $produtos->fetch_assoc()) {
                $selected = (isset($_POST['id_prod']) && $_POST['id_prod'] == $produto['id_prod']) ? 'selected' : '';
                echo "<option value='{$produto['id_prod']}' $selected>{$produto['nome_prod']}</option>";
            }
            ?>
        </select><br><br>

        <label for="preco_itens_ped">Preço:</label><br>
        <input type="text" name="preco_itens_ped"
            value="<?= isset($_POST['preco_itens_ped']) ? htmlspecialchars($_POST['preco_itens_ped']) : '' ?>"
            required><br><br>

        <button type="submit"><?= (isset($_POST['id_itens_ped'])) ? 'Salvar' : 'Cadastrar' ?></button>
    </form>

    <hr>

    <!-- Exibição dos itens de pedido -->
    <h2>Lista de Itens de Pedido</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>ID Produto</th>
                <th>Nome do Produto</th>
                <th>Data do Pedido</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['id_ped']) ?></td>
                    <td><?= htmlspecialchars($item['id_prod']) ?></td>
                    <td><?= htmlspecialchars($item['nome_prod']) ?></td>
                    <td><?= htmlspecialchars($item['data_ped']) ?></td>
                    <td><?= htmlspecialchars($item['preco_itens_ped']) ?></td>
                    <td>
                        <a href="forms_itens_ped.php?id_ped=<?= $item['id_ped'] ?>&id_prod=<?= $item['id_prod'] ?>"onclick="return confirm('Tem certeza que deseja remover este item de pedido?')">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>