<?php
    include_once '../secure/verifica_login/session.php'; // Verificando se você está logado
    include_once "../secure/includes/dbconnect.php";
    $erro = '';
    $success = '';

    // Inserir/Atualizar Serviço
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["nome_serv"], $_POST["preco_serv"], $_POST["prazo_serv"])) {
            if (empty($_POST["nome_serv"]) || empty($_POST["preco_serv"]) || empty($_POST["prazo_serv"])) {
                $erro = "Todos os campos são obrigatórios.";
            } else {
                $id_serv = $_POST["id_serv"] ?? -1; // Usar null coalescing operator para evitar undefined index
                $nome_serv = $_POST["nome_serv"];
                $descricao_serv = $_POST["descricao_serv"] ?? ''; // Valor padrão se não estiver setado
                $preco_serv = $_POST["preco_serv"];
                $status_serv = 'ativo';
                $prazo_serv = date('Y-m-d H:i:s', strtotime("+{$_POST["prazo_serv"]} days"));

                // Inserir novo serviço
                if ($id_serv == -1) {
                    $stmt = $con->prepare("INSERT INTO Servico (nome_serv, desc_serv, preco_serv, prazo_serv, status_serv) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssds", $nome_serv, $descricao_serv, $preco_serv, $prazo_serv, $status_serv);
                    $success = $stmt->execute() ? "Serviço cadastrado com sucesso." : "Erro ao cadastrar serviço: " . $stmt->error;
                } else { // Atualizar serviço existente
                    $stmt = $con->prepare("UPDATE Servico SET nome_serv = ?, desc_serv = ?, preco_serv = ?, prazo_serv = ? WHERE id_serv = ?");
                    $stmt->bind_param("ssdsi", $nome_serv, $descricao_serv, $preco_serv, $prazo_serv, $status_serv, $id_serv);
                    $success = $stmt->execute() ? "Serviço atualizado com sucesso." : "Erro ao atualizar serviço: " . $stmt->error;
                }
            }
        } else {
            $erro = "Todos os campos são obrigatórios.";
        }
    }

    // Desabilitar Serviço
    if (isset($_GET["id_serv"]) && is_numeric($_GET["id_serv"]) && isset($_GET["del"])) {
        $id_serv = (int)$_GET["id_serv"];
        $stmt = $con->prepare("UPDATE Servico SET status_serv = 'desabilitado' WHERE id_serv = ?");
        $stmt->bind_param('i', $id_serv);
        $success = $stmt->execute() ? "Serviço desabilitado com sucesso." : "Erro ao desabilitar serviço: " . $stmt->error;
    }

    // Listar Serviços
    $result = $con->query("SELECT * FROM Servico WHERE status_serv = 'ativo'");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>+Serviços | Salão Novo Estilo</title>
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="styles/forms.css">
</head>
<?php 
    include_once "includes/nav.php"; 
?>
<body>
    <div class="container">
        <h1>Cadastro de Serviços</h1>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Formulário para adicionar ou editar serviço -->
        <form action="forms_serv.php" method="POST">
            <input type="hidden" name="id_serv" value="<?= htmlspecialchars($_POST['id_serv'] ?? -1) ?>">

            <label for="nome_serv">Nome do Serviço:</label>
            <input type="text" name="nome_serv" value="<?= htmlspecialchars($_POST['nome_serv'] ?? '') ?>" required>

            <label for="descricao_serv">Descrição:</label>
            <input type="text" name="descricao_serv" value="<?= htmlspecialchars($_POST['descricao_serv'] ?? '') ?>">

            <label for="preco_serv">Preço:</label>
            <input type="number" step="0.01" name="preco_serv" value="<?= htmlspecialchars($_POST['preco_serv'] ?? '') ?>" required>

            <label for="prazo_serv">Prazo (dias):</label>
            <input type="number" name="prazo_serv" value="<?= htmlspecialchars($_POST['prazo_serv'] ?? '') ?>" required><br><br>

            <button type="submit"><?= isset($_POST['id_serv']) && $_POST['id_serv'] != -1 ? 'Salvar' : 'Cadastrar' ?></button>
        </form>

        <hr>

        <!-- Exibição dos serviços -->
        <h2>Lista de Serviços</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Prazo (dias)</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($servico = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($servico['id_serv']) ?></td>
                        <td><?= htmlspecialchars($servico['nome_serv']) ?></td>
                        <td><?= htmlspecialchars($servico['desc_serv']) ?></td>
                        <td><?= htmlspecialchars($servico['preco_serv']) ?></td>
                        <td><?= htmlspecialchars($servico['prazo_serv']) ?></td>
                        <td><?= htmlspecialchars($servico['status_serv']) ?></td>
                        <td>
                            <a href="forms_serv.php?id_serv=<?= $servico['id_serv'] ?>" class="edit">Editar</a> |
                            <a href="?id_serv=<?= $servico['id_serv'] ?>&del=true" onclick="return confirm('Tem certeza que deseja desabilitar este serviço?');">Desabilitar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>