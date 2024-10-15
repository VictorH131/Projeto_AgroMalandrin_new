<?php
    include_once '../secure/verifica_login/session.php';  // Verificando se você está logado
    include_once "../secure/includes/dbconnect.php";

    $erro = '';
    $success = '';

    // Inserir/Atualizar Pedido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["data_ped"], $_POST["endereco_entrega"], $_POST["data_entrega_ped"], $_POST["id_cli"], $_SESSION["id"])) {
            if (empty($_POST["data_ped"]) || empty($_POST["endereco_entrega"]) || empty($_POST["data_entrega_ped"]) || empty($_POST["id_cli"]) || empty($_SESSION["id"])) {
                $erro = "Todos os campos são obrigatórios.";
            } else {
                $data_ped = $_POST["data_ped"];
                $endereco_entrega = $_POST["endereco_entrega"];
                $data_entrega_ped = $_POST["data_entrega_ped"];
                $id_cli = $_POST["id_cli"];
                $id_usu = $_SESSION["id"];
                $id_ped = isset($_POST["id_ped"]) ? $_POST["id_ped"] : null;
    
                // Verificar se é inserção ou atualização
                if ($id_ped === null) { // Inserir novo pedido
                    $stmt = $con->prepare("INSERT INTO Pedido (data_ped, endereco_entrega, data_entrega_ped, id_cli, id_usu) VALUES (?, ?, ?, ?, ?)");
                    if ($stmt === false) {
                        $erro = "Erro ao preparar a consulta: " . $con->error;  // Usar $con->error
                    } else {
                        $stmt->bind_param("ssiii", $data_ped, $endereco_entrega, $data_entrega_ped, $id_cli, $id_usu);
                        if ($stmt->execute()) {
                            $success = "Pedido registrado com sucesso.";
                        } else {
                            $erro = "Erro ao registrar pedido: " . $stmt->error;  // Usar $stmt->error para detalhes
                        }
                    }
                } else { // Atualizar pedido existente
                    $stmt = $con->prepare("UPDATE Pedido SET data_ped = ?, endereco_entrega = ?, data_entrega_ped = ?, id_cli = ?, id_usu = ? WHERE id_ped = ?");
                    if ($stmt === false) {
                        $erro = "Erro ao preparar a consulta: " . $con->error;  // Usar $con->error
                    } else {
                        $stmt->bind_param("ssiiii", $data_ped, $endereco_entrega, $data_entrega_ped, $id_cli, $id_usu, $id_ped);
                        if ($stmt->execute()) {
                            $success = "Pedido atualizado com sucesso.";
                        } else {
                            $erro = "Erro ao atualizar pedido: " . $stmt->error;  // Usar $stmt->error para detalhes
                        }
                    }
                }
            }
        } else {
            $erro = "Todos os campos são obrigatórios.";
        }
    }
    

    // Remover Pedido
    if (isset($_GET["id_ped"]) && is_numeric($_GET["id_ped"])) {
        $id_ped = (int) $_GET["id_ped"];

        $stmt = $con->prepare("DELETE FROM Pedido WHERE id_ped = ?");
        $stmt->bind_param('i', $id_ped);
        if ($stmt->execute()) {
            $success = "Pedido removido com sucesso.";
        } else {
            $erro = "Erro ao remover pedido: " . $stmt->error;
        }
    }

    // Listar Pedidos
    $result = $con->query("SELECT p.*, c.nome_cli, u.nome_usu FROM Pedido p LEFT JOIN Cliente c ON p.id_cli = c.id_cli LEFT JOIN Usuario u ON p.id_usu = u.id_usu");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>+Pedido | Salão Novo Estilo</title>
    <link rel="shortcut icon" href="" type="image/x-icon">     
    <link rel="stylesheet" href="styles/forms.css">
</head>
<?php 
    include_once "includes/nav.php"; 
?>
<body>
    <div class="container">

    <h1>Cadastro de Pedidos</h1>

   
    <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>


    <!-- Formulário para adicionar ou editar pedido -->
    <form action="forms_ped.php" method="POST">
        <input type="hidden" name="id_ped" value="<?= isset($_POST['id_ped']) ? $_POST['id_ped'] : '' ?>">

        <input type="hidden" name="data_ped" value="<?= date('Y-m-d H:i:s') ?>" required>

        <label for="endereco_entrega">Endereço de Entrega:</label>
        <input type="text" name="endereco_entrega"
            value="<?= isset($_POST['endereco_entrega']) ? htmlspecialchars($_POST['endereco_entrega']) : '' ?>"
            required>

        <label for="data_entrega_ped">Data de Entrega:</label>
        <input type="date" name="data_entrega_ped"
            value="<?= isset($_POST['data_entrega_ped']) ? htmlspecialchars($_POST['data_entrega_ped']) : '' ?>">

        <label for="id_cli">Cliente:</label>
        <select name="id_cli" required>
            <option value="">Selecione um cliente</option>
            <?php
            // Listar clientes para o dropdown
            $clientes = $con->query("SELECT id_cli, nome_cli FROM Cliente");
            while ($cliente = $clientes->fetch_assoc()) {
                $selected = (isset($_POST['id_cli']) && $_POST['id_cli'] == $cliente['id_cli']) ? 'selected' : '';
                echo "<option value='{$cliente['id_cli']}' $selected>{$cliente['nome_cli']}</option>";
            }
            ?>
        </select>

        <label for="id_usu">Usuário:</label>
        <input name="id_usu" value="<?php echo $_SESSION['nome'] ?>" disabled><br><br>

        <button type="submit"><?= (isset($_POST['id_ped'])) ? 'Salvar' : 'Cadastrar' ?></button>
    </form>

    <hr>

    <!-- Exibição dos pedidos -->
    <h2>Lista de Pedidos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Data do Pedido</th>
                <th>Endereço de Entrega</th>
                <th>Data de Entrega</th>
                <th>Cliente</th>
                <th>Usuário</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($pedido = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($pedido['id_ped']) ?></td>
                        <td><?= htmlspecialchars($pedido['data_ped']) ?></td>
                        <td><?= htmlspecialchars($pedido['endereco_entrega']) ?></td>
                        <td><?= htmlspecialchars($pedido['data_entrega_ped']) ?></td>
                        <td><?= htmlspecialchars($pedido['nome_cli']) ?></td>
                        <td><?= htmlspecialchars($pedido['nome_usu']) ?></td>
                        <td>
                            <a href="forms_ped.php?id_ped=<?= $pedido['id_ped'] ?>" onclick="return confirm('Tem certeza que deseja remover este pedido?')">Remover</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Nenhum pedido encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>