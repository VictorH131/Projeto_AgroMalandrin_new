                            <!-- ATENÇÃO - ARRUMAR OS ERROS PRESENTES NO FORMS_ITENS_COMPRA -->

<?php
    include_once '../secure/verifica_login/session.php';  // Verificando se você está logado
    include_once "../secure/includes/dbconnect.php";

    $erro = '';
    $success = '';

    //Inserir/Atualizar Itens de Compra
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["id_compra"], $_POST["id_prod"], $_POST["preco"])) {
            if (empty($_POST["id_compra"]) || empty($_POST["id_prod"]) || empty($_POST["preco"])) {
                $erro = "Todos os campos são obrigatórios.";
            } else {
                $id_compra = $_POST["id_compra"];
                $id_prod = $_POST["id_prod"];
                $preco = $_POST["preco"];
                $id_item = isset($_POST["id_item"]) ? $_POST["id_item"] : null;

                //Validar se o preço é um número
                if (!is_numeric($preco)) {
                    $erro = "O preço deve ser um número.";
                } else {
                    if ($id_item === null) { //Inserir novo item
                        $stmt = $con->prepare("INSERT INTO Items_compra (id_compra, id_prod, preco_items_compra) VALUES (?, ?, ?)");
                        $stmt->bind_param("iis", $id_compra, $id_prod, $preco);

                        if ($stmt->execute()) {
                            $success = "Item de compra registrado com sucesso.";
                        } else {
                            $erro = "Erro ao registrar item de compra: " . $stmt->error;
                        }
                    } else { // Atualizar item existente
                        $stmt = $con->prepare("UPDATE Items_compra SET preco_items_compra = ? WHERE id_compra = ? AND id_prod = ?");
                        $stmt->bind_param("dii", $preco, $id_compra, $id_prod);

                        if ($stmt->execute()) {
                            $success = "Item de compra atualizado com sucesso.";
                        } else {
                            $erro = "Erro ao atualizar item de compra: " . $stmt->error;
                        }
                    }
                }
            }
        } else {
            $erro = "Todos os campos são obrigatórios.";
        }
    }

    //Remover Item de Compra
    if (isset($_GET["id_compra"], $_GET["id_prod"])) {
        $id_compra = (int) $_GET["id_compra"];
        $id_prod = (int) $_GET["id_prod"];

        $stmt = $con->prepare("DELETE FROM Items_compra WHERE id_compra = ? AND id_prod = ?");
        $stmt->bind_param('ii', $id_compra, $id_prod);
        if ($stmt->execute()) {
            $success = "Item de compra removido com sucesso.";
        } else {
            $erro = "Erro ao remover item de compra: " . $stmt->error;
        }
    }

    //Listar Itens de Compra
    $result = $con->query("SELECT ic.*, p.nome_prod FROM Items_compra ic LEFT JOIN Produto p ON ic.id_prod = p.id_prod");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens Compra | Agro Malandrin</title>
    <link rel="shortcut icon" href="" type="image/x-icon">
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

    <!-- Formulário para adicionar ou editar item de compra -->
    <form action="forms_itens_compra.php" method="POST">
        <input type="hidden" name="id_item" value="<?= isset($_POST['id_item']) ? $_POST['id_item'] : '' ?>">

        <label for="id_compra">ID da Compra:</label>
        <input type="number" name="id_compra" min="1"
            value="<?= isset($_POST['id_compra']) ? htmlspecialchars($_POST['id_compra']) : '' ?>" required>

        <label for="id_prod">ID do Produto:</label>
        <input type="number" name="id_prod" min="1"
            value="<?= isset($_POST['id_prod']) ? htmlspecialchars($_POST['id_prod']) : '' ?>" required>

            <label for="preco_">Preço:</label>
        <input type="text" id="preco" name="preco" placeholder="R$ 0,00"
            value="<?= isset($_POST['preco']) ? htmlspecialchars($_POST['preco']) : '' ?>"
            required><br><br>

        <script>
            document.getElementById('preco').addEventListener('input', function (e) {
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

        <button type="submit"><?= (isset($_POST['id_item'])) ? 'Salvar' : 'Cadastrar' ?></button>
    </form>

    <hr>

    <!-- Exibição dos itens de compra -->
    <h2>Lista de Itens de Compra</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID da Compra</th>
                <th>ID do Produto</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($item = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['id_compra']) ?></td>
                    <td><?= htmlspecialchars($item['id_prod']) ?></td>
                    <td><?= htmlspecialchars($item['preco_items_compra']) ?></td>
                    <td>
                        <a href="forms_itens_compra.php?id_compra=<?= $item['id_compra'] ?>&id_prod=<?= $item['id_prod'] ?>"
                            onclick="return confirm('Tem certeza que deseja remover este item de compra?')">Remover</a>
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