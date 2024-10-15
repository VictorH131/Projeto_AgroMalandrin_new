<?php
    include_once '../secure/verifica_login/session.php';  // Verificando se você está logado
    include_once "../secure/includes/dbconnect.php";

    $erro = '';
    $success = '';
    
    // Inserir/Atualizar Compra
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["data_compra"], $_POST["id_for"], $_SESSION['id'], $_POST["prev_entrega"], $_POST["preco_compra"])) {
            if (empty($_POST["data_compra"]) || empty($_POST["id_for"]) || empty($_SESSION['id']) || empty($_POST["prev_entrega"]) || empty($_POST["preco_compra"])) {
                $erro = "Todos os campos são obrigatórios.";
            } else {
                $id_compra = isset($_POST["id_compra"]) ? $_POST["id_compra"] : -1;
    
                // Verifique se o campo data_compra foi preenchido
                $data_compra = $_POST["data_compra"]; // pega a data do formulário ou a data atual
    
                $id_for = $_POST["id_for"];
                $id_usu = $_SESSION["id"];
                $prev_entrega = $_POST["prev_entrega"];
                $preco_compra = $_POST["preco_compra"];
                $data_entrega_efetiva = !empty($_POST["data_entrega_efetiva"]) ? $_POST["data_entrega_efetiva"] : null;
    
                if ($id_compra == -1) { // Inserir nova compra
                    $stmt = $con->prepare("INSERT INTO Compra (data_compra, id_for, id_usu, prev_entrega, data_entrega_efetiva, preco_compra) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("siisds", $data_compra, $id_for, $id_usu, $prev_entrega, $data_entrega_efetiva, $preco_compra);
    
                    if ($stmt->execute()) {
                        $success = "Compra registrada com sucesso.";
                    } else {
                        $erro = "Erro ao registrar compra: " . $stmt->error;
                    }
                } else { // Atualizar compra existente
                    $stmt = $con->prepare("UPDATE Compra SET data_compra = ?, id_for = ?, id_usu = ?, prev_entrega = ?, data_entrega_efetiva = ?, preco_compra = ? WHERE id_compra = ?");
                    $stmt->bind_param("siisdis", $data_compra, $id_for, $id_usu, $prev_entrega, $data_entrega_efetiva, $preco_compra, $id_compra);
    
                    if ($stmt->execute()) {
                        $success = "Compra atualizada com sucesso.";
                    } else {
                        $erro = "Erro ao atualizar compra: " . $stmt->error;
                    }
                }
            }
        } else {
            $erro = "Todos os campos são obrigatórios.";
        }
    }
    
    // Remover Compra
    if (isset($_GET["id_compra"]) && is_numeric($_GET["id_compra"])) {
        $id_compra = (int) $_GET["id_compra"];
    
        $stmt = $con->prepare("DELETE FROM Compra WHERE id_compra = ?");
        $stmt->bind_param('i', $id_compra);
        if ($stmt->execute()) {
            $success = "Compra removida com sucesso.";
        } else {
            $erro = "Erro ao remover compra: " . $stmt->error;
        }
    }
    
    // Listar Compras
    $result = $con->query("SELECT c.*, f.nome_for, u.nome_usu FROM Compra c LEFT JOIN Fornecedor f ON c.id_for = f.id_for LEFT JOIN Usuario u ON c.id_usu = u.id_usu");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens Compra | Agro Malandrin</title>
    <!-- colocando o icone da pagina-->
    <link rel="shortcut icon" href="../multimidia/icones/planta.png" type="image/x-icon">
    <link rel="stylesheet" href="styles/forms.css">
</head>
<?php 
    include_once "includes/nav.php"; 
?>
<body>
    <div class="container">

    <h1>Cadastro de Itens de Compra</h1>

    <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

    <!-- Formulário para adicionar ou editar compra -->
    <form action="compra.php" method="POST">
        <input type="hidden" name="id_compra" value="<?= isset($_GET['id_compra']) ? $_GET['id_compra'] : -1 ?>">

        <!-- Campo para a data da compra -->
        <input type="hidden" name="data_compra" value="<?= date('Y-m-d H:i:s') ?>">

        <label for="id_for">Fornecedor:</label>
        <select name="id_for" required>
            <option value="">Selecione um fornecedor</option>
            <?php
            // Listar fornecedores para o dropdown
            $fornecedores = $con->query("SELECT id_for, nome_for FROM Fornecedor");
            while ($fornecedor = $fornecedores->fetch_assoc()) {
                $selected = (isset($_POST['id_for']) && $_POST['id_for'] == $fornecedor['id_for']) ? 'selected' : '';
                echo "<option value='{$fornecedor['id_for']}' $selected>{$fornecedor['nome_for']}</option>";
            }
            ?>
        </select>

        <label for="id_usu">Usuário:</label>
        <input name="id_usu" type="text" value="<?php echo $_SESSION['nome'] ?>" disabled>

        <label for="prev_entrega">Previsão de Entrega:</label>
        <input type="date" name="prev_entrega"
            value="<?= isset($_POST['prev_entrega']) ? htmlspecialchars($_POST['prev_entrega']) : '' ?>"
            required>

        <label for="preco_compra">Preço da Compra:</label>
        <input type="text" id="preco_compra" name="preco_compra" placeholder="R$ 0,00"
            value="<?= isset($_POST['preco_compra']) ? htmlspecialchars($_POST['preco_compra']) : '' ?>"
            required>

        <script>
            document.getElementById('preco_compra').addEventListener('input', function (e) {
                // Remove qualquer caractere não numérico
                let value = e.target.value.replace(/[^0-9]/g, '');

                // Se o valor estiver vazio, não faz nada
                if (value === '') {
                    e.target.value = '';
                    return;
                }

                // Define a parte decimal (centavos)
                let decimalPart = value.slice(-2).padStart(2, '0');
                // Define a parte inteira (reais)
                let integerPart = value.slice(0, -2);

                // Remove zeros à esquerda da parte inteira
                integerPart = integerPart.replace(/^0+/, '') || '0'; // Se estiver vazio, torna-se '0'

                // Adiciona separador de milhar
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Formata o valor final
                let formattedValue = integerPart + ',' + decimalPart;

                // Define o valor formatado no campo
                e.target.value = 'R$ ' + formattedValue;
            });

        </script>

        <label for="data_entrega_efetiva">Data de Entrega Efetiva (opcional):</label>
        <input type="date" name="data_entrega_efetiva"
            value="<?= isset($_POST['data_entrega_efetiva']) ? htmlspecialchars($_POST['data_entrega_efetiva']) : '' ?>"><br><br>

        <button
            type="submit"><?= (isset($_POST['id_compra']) && $_POST['id_compra'] != -1) ? 'Salvar' : 'Cadastrar' ?></button>
    </form>

    <hr>

    <!-- Exibição das compras -->
    <h2>Lista de Compras</h2>
    <table border="1">
        <thead>
            <tr>
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
            <?php while ($compra = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($compra['id_compra']) ?></td>
                    <td><?= htmlspecialchars($compra['data_compra']) ?></td>
                    <td><?= htmlspecialchars($compra['nome_for']) ?></td>
                    <td><?= htmlspecialchars($compra['nome_usu']) ?></td>
                    <td><?= htmlspecialchars($compra['prev_entrega']) ?></td>
                    <td><?= htmlspecialchars($compra['data_entrega_efetiva']) ?></td>
                    <td><?= htmlspecialchars($compra['preco_compra']) ?></td>
                    <td>
                        <a href="compra.php?id_compra=<?= $compra['id_compra'] ?>">Editar</a> |
                        <a href="compra.php?id_compra=<?= $compra['id_compra'] ?>&delete=true"
                            onclick="return confirm('Tem certeza que deseja remover esta compra?')">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>