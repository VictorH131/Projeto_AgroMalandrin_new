<?php
    include_once '../secure/verifica_login/session.php';  // Verificando se você está logado
    include_once "../secure/includes/dbconnect.php";

    $erro = '';
    $success = '';

    // Inserir/Atualizar Item de Ordem de Serviço
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["id_ordem"], $_POST["id_serv"], $_POST["preco_items_os"])) {
            if (empty($_POST["id_ordem"]) || empty($_POST["id_serv"]) || empty($_POST["preco_items_os"])) {
                $erro = "Todos os campos são obrigatórios.";
            } else {
                $id_ordem = (int) $_POST["id_ordem"];
                $id_serv = (int) $_POST["id_serv"];
                $preco_items_os = (float) $_POST["preco_items_os"];

                // Verificar se estamos atualizando ou inserindo
                if (isset($_POST['id_items_os']) && $_POST['id_items_os'] != -1) { // Atualizar item existente
                    $stmt = $con->prepare("UPDATE Items_os SET preco_items_os = ? WHERE id_ordem = ? AND id_serv = ?");
                    $stmt->bind_param("dii", $preco_items_os, $id_ordem, $id_serv);

                    if ($stmt->execute()) {
                        $success = "Item de ordem de serviço atualizado com sucesso.";
                    } else {
                        $erro = "Erro ao atualizar item de ordem de serviço: " . $stmt->error;
                    }
                } else { // Inserir novo item
                    $stmt = $con->prepare("INSERT INTO Items_os (id_ordem, id_serv, preco_items_os) VALUES (?, ?, ?)");
                    $stmt->bind_param("iid", $id_ordem, $id_serv, $preco_items_os);

                    if ($stmt->execute()) {
                        $success = "Item de ordem de serviço cadastrado com sucesso.";
                    } else {
                        $erro = "Erro ao cadastrar item de ordem de serviço: " . $stmt->error;
                    }
                }
            }
        } else {
            $erro = "Todos os campos são obrigatórios.";
        }
    }

            // CODIGO QUE PRECISA SER REVISADO --------------------------------------------------------------------------

    // // Remover Item de Ordem de Serviço
    // if (isset($_GET["id_ordem"], $_GET["id_serv"]) && is_numeric($_GET["id_ordem"]) && is_numeric($_GET["id_serv"])) {
    //     $id_ordem = (int) $_GET["id_ordem"];
    //     $id_serv = (int) $_GET["id_serv"];

    //     $stmt = $con->prepare("DELETE FROM Items_os WHERE id_ordem = ? AND id_serv = ?");
    //     $stmt->bind_param('ii', $id_ordem, $id_serv);
    //     if ($stmt->execute()) {
    //         $success = "Item de ordem de serviço removido com sucesso.";
    //     } else {
    //         $erro = "Erro ao remover item de ordem de serviço: " . $stmt->error;
    //     }
    // }

    // ---------------------------------------------------------------------------------------------------------------------
    
    // Listar Itens de Ordem de Serviço
    $result = $con->query("SELECT io.*, s.nome_serv FROM Items_os io LEFT JOIN Servico s ON io.id_serv = s.id_serv");

    // Listar ordens de serviço e serviços
    $ordens = $con->query("SELECT id_ordem FROM Ordem_servico");
    $servicos = $con->query("SELECT id_serv, nome_serv FROM Servico");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens OS | Salão Novo Estilo</title>
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="styles/forms.css">
</head>
<?php
    include_once "includes/nav.php";
?>
<body>
    <div class="container">
    <h1>Cadastro de Itens de Ordem de Serviço</h1>

    <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

    <!-- Formulário para adicionar ou editar item de ordem de serviço -->
    <form action="forms_itens_os.php" method="POST">
        <input type="hidden" name="id_items_os" value="<?= isset($_POST['id_items_os']) ? $_POST['id_items_os'] : -1 ?>">

        <!-- Dropdown para Ordem de Serviço -->
        <label for="id_ordem">Ordem de Serviço:</label>
        <select name="id_ordem" required>
            <option value="">Selecione uma ordem de serviço</option>
            <?php
            // Preencher o dropdown com as ordens de serviço
            while ($ordem = $ordens->fetch_assoc()) {
                $selected = (isset($_POST['id_ordem']) && $_POST['id_ordem'] == $ordem['id_ordem']) ? 'selected' : '';
                echo "<option value='{$ordem['id_ordem']}' $selected>{$ordem['id_ordem']}</option>";
            }
            ?>
        </select>

        <!-- Dropdown para Serviço -->
        <label for="id_serv">Serviço:</label>
        <select name="id_serv" required>
            <option value="">Selecione um serviço</option>
            <?php
            // Preencher o dropdown com os serviços
            while ($servico = $servicos->fetch_assoc()) {
                $selected = (isset($_POST['id_serv']) && $_POST['id_serv'] == $servico['id_serv']) ? 'selected' : '';
                echo "<option value='{$servico['id_serv']}' $selected>{$servico['nome_serv']}</option>";
            }
            ?>
        </select>

        <!-- Campo para Preço -->
        <label for="preco_items_os">Preço:</label>
        <input type="number" step="0.01" name="preco_items_os"
            value="<?= isset($_POST['preco_items_os']) ? htmlspecialchars($_POST['preco_items_os']) : '' ?>"
            required>

        <!-- Botão de Submissão -->
        <button type="submit"><?= (isset($_POST['id_items_os']) && $_POST['id_items_os'] != -1) ? 'Salvar' : 'Cadastrar' ?></button>
    </form>

    <hr>

    <!-- Exibição dos itens de ordem de serviço -->
    <h2>Lista de Itens de Ordem de Serviço</h2>
    <table>
        <thead>
            <tr>
                <th>ID Ordem Serviço</th>
                <th>ID Serviço</th>
                <th>Nome Serviço</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['id_ordem']) ?></td>
                    <td><?= htmlspecialchars($item['id_serv']) ?></td>
                    <td><?= htmlspecialchars($item['nome_serv']) ?></td>
                    <td><?= htmlspecialchars($item['preco_items_os']) ?></td>
                    <td>
                        <a href="forms_itens_os.php?id_ordem=<?= $item['id_ordem'] ?>&id_serv=<?= $item['id_serv'] ?>" 
                        onclick="return confirm('Tem certeza que deseja remover este item?')">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
