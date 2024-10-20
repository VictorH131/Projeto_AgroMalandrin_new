<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);
$success = '';
$aviso = '';

// Inserir Ordem de Serviço
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_cli"], $_POST["id_usu"])) {
        // Verifica se algum campo obrigatório está vazio
        if (empty($_POST["id_cli"]) || empty($_POST["id_usu"])) {
            $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Captura dados do formulário
            $id_cli = $_POST["id_cli"];
            $id_usu = $_POST["id_usu"];
            $data_ordem_servico = date('Y-m-d H:i:s'); // Capturando a data e hora atuais

            // Preparar a inserção
            $stmt = $conn->prepare("INSERT INTO Ordem_servico (id_cli, id_usu, data_ordem_servico) VALUES (?, ?, ?)");

            // Bind de todos os parâmetros
            $stmt->bind_param("iis", $id_cli, $id_usu, $data_ordem_servico);

            // Executar a inserção
            if ($stmt->execute()) {
                $success = "Ordem de Serviço cadastrada com sucesso.";
            } else {
                $aviso = "Erro ao cadastrar Ordem de Serviço: " . $stmt->error;
            }
        }
    } else {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    }
}



// Consulta para buscar ordens de serviço com nomes
$sql = "SELECT os.id_ordem, c.nome_cli, u.nome_usu, os.data_ordem_servico
        FROM Ordem_servico os
        JOIN Cliente c ON os.id_cli = c.id_cli
        JOIN Usuario u ON os.id_usu = u.id_usu"; // Verifique os nomes das tabelas e campos

$result = $conn->query($sql);

// Consulta para obter a lista de clientes
$clientes = $conn->query("SELECT id_cli, nome_cli FROM Cliente");

// Consulta para obter a lista de usuários
$usuarios = $conn->query("SELECT id_usu, nome_usu FROM Usuario");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordens de Serviço | Agro Malandrin</title>
    <style>
        .id-column {
            width: 80px; /* Tamanho fixo para a coluna ID */
        }
    </style>
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

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box"><br>
                    <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Ordem de Serviço</h3>
                    <form action="os.php" method="POST">
                        <label for="id_cli">Cliente:</label>
                        <select id="id_cli" name="id_cli" required class="form-control">
                            <option value="">Selecione um cliente</option>
                            <?php while ($cliente = $clientes->fetch_assoc()): ?>
                                <option value="<?= $cliente['id_cli'] ?>"><?= htmlspecialchars($cliente['nome_cli']) ?></option>
                            <?php endwhile; ?>
                        </select><br>

                        <label for="id_usu">Usuário:</label>
                        <select id="id_usu" name="id_usu" required class="form-control">
                            <option value="">Selecione um usuário</option>
                            <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                                <option value="<?= $usuario['id_usu'] ?>"><?= htmlspecialchars($usuario['nome_usu']) ?></option>
                            <?php endwhile; ?>
                        </select><br>

                        <input type="hidden" name="data_ordem_servico" value="<?= date('Y-m-d H:i:s') ?>"> <!-- Campo oculto para a data -->

                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>

        <hr>

        <div class="container">
                <div class="row">
                    <div class="col-md-5 mx-auto">
                        <div class="box">
                                    
                <h3>Ordens de Serviço</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table-success">
                                <th class="id-column">ID</th>
                                <th>Cliente</th>
                                <th>Usuário</th>
                                <th>Data OS</th> <!-- Adicionando coluna de Data OS -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id_ordem']) ?></td> 
                                        <td><?= htmlspecialchars($row['nome_cli']) ?></td>
                                        <td><?= htmlspecialchars($row['nome_usu']) ?></td>
                                        <td><?= htmlspecialchars($row['data_ordem_servico']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Nenhuma ordem de serviço encontrada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
