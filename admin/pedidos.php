<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);
$success = '';
$aviso = '';

// Consultas para buscar os clientes e usuários
$sql_clientes = "SELECT id_cli, nome_cli FROM Cliente";
$clientes = $conn->query($sql_clientes);

$sql_usuarios = "SELECT id_usu, nome_usu FROM Usuario";
$usuarios = $conn->query($sql_usuarios);

// Inserir/Atualizar Pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["data_ped"], $_POST["id_cli"], $_POST["id_usu"], $_POST["endereco_entrega"], $_POST["data_entrega_ped"])) {
        // Verifica se algum campo obrigatório está vazio
        if (empty($_POST["data_ped"]) || empty($_POST["id_cli"]) || empty($_POST["id_usu"]) || empty($_POST["endereco_entrega"]) || empty($_POST["data_entrega_ped"])) {
            $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Captura dados do formulário
            $data_ped = $_POST["data_ped"];
            $id_cli = $_POST["id_cli"]; // ID do cliente selecionado
            $id_usu = $_POST["id_usu"]; // ID do usuário selecionado
            $endereco_entrega = $_POST["endereco_entrega"];
            $data_entrega_ped = $_POST["data_entrega_ped"];

            // Preparar a inserção
            $stmt = $conn->prepare("INSERT INTO Pedido (data_ped, id_cli, id_usu, endereco_entrega, data_entrega_ped) VALUES (?, ?, ?, ?, ?)");
            
            // Bind dos parâmetros
            $stmt->bind_param("siiss", $data_ped, $id_cli, $id_usu, $endereco_entrega, $data_entrega_ped);

            // Executar a inserção
            if ($stmt->execute()) {
                $success = "Pedido cadastrado com sucesso.";
            } else {
                $aviso = "Erro ao cadastrar pedido: " . $stmt->error;
            }
        }
    } else {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    }
}

// Consulta para buscar pedidos com join para obter os nomes de clientes e usuários
$sql = "
    SELECT Pedido.id_ped, Pedido.data_ped, Cliente.nome_cli, Usuario.nome_usu, Pedido.endereco_entrega, Pedido.data_entrega_ped 
    FROM Pedido 
    JOIN Cliente ON Pedido.id_cli = Cliente.id_cli 
    JOIN Usuario ON Pedido.id_usu = Usuario.id_usu
";
$pedidos = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos | Agro Malandrin</title>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-end mb-3 fixed-top">
            <a href="#formulario" class="btn btn-outline-primary me-2">Ir para Formulário</a>
            <a href="#tabela-pedidos" class="btn btn-outline-secondary">Ir para Tabela</a>
        </div>

        <!-- Exibir mensagens de aviso ou sucesso -->
        <?php if (!empty($aviso)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($aviso) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="box">
                        <div >
                            <br><h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Pedido</h3><br>

                            <!-- Inicio do Formulario de Pedidos -->
                            <form action="pedidos.php" method="POST" id="formulario">
                                <label for="data_ped">Data do Pedido:</label>
                                <input type="datetime-local" id="data_ped" name="data_ped" required class="form-control"><br>

                                <div class="d-flex mt-0">
                                    <div class="row">
                                        <div class="col">
                                            <label for="id_cli">Cliente:</label>
                                            <select id="id_cli" name="id_cli" required class="form-control" style="width: 300px;">
                                                <option value="" disabled selected>Selecione o cliente</option>
                                                <?php while ($row = $clientes->fetch_assoc()): ?>
                                                    <option value="<?= $row['id_cli'] ?>"><?= htmlspecialchars($row['nome_cli']) ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="col">
                                            <label for="id_usu" style="position: 10%;">Usuário:</label>
                                            <select id="id_usu" name="id_usu" required class="form-control" style="width: 300px; position: 10%;">
                                                <option value="" disabled selected>Selecione o usuário</option>
                                                <?php while ($row = $usuarios->fetch_assoc()): ?>
                                                    <option value="<?= $row['id_usu'] ?>"><?= htmlspecialchars($row['nome_usu']) ?></option>
                                                <?php endwhile; ?>
                                            </select><br>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mt-0">
                                    <div class="row">
                                        <div class="col">
                                            <label for="endereco_entrega">Endereço de Entrega:</label>
                                            <input type="text" id="endereco_entrega" name="endereco_entrega" required class="form-control" style="width: 300px;">
                                        </div>

                                        <div class="col">
                                            <label for="data_entrega_ped" style="position: 10%;">Data de Entrega:</label>
                                            <input type="datetime-local" id="data_entrega_ped" name="data_entrega_ped" required class="form-control" style="width: 300px; position: 10%;"><br>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" onclick="redirecionarOrdemServico()" class="btn btn-success">Cadastrar Pedido</button>

                                <script>
                                    function redirecionarOrdemServico() {
                                    // Redireciona para a página de Ordem de Serviço
                                    window.location.href = 'os.php';
                                    }
                                </script>

                            </form><br><br>
                            <!-- Fim do Formulario de Pedidos -->
                        </div>
                    </div>
                </div>
            </div>
            <hr><br><br>    

            <!-- Tabela de Pedidos -->
            <div class="container">
                <div class="row">
                    <div class="box">
                        <div id="tabela-pedidos">
                            <h3 style="margin-left: 184px;">Lista de Pedidos</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered" style="width: 70%; margin: auto;">
                                    <thead>
                                        <tr class="table-success">
                                            <th style="width: 2%;">ID</th>
                                            <th style="width: 150px;">Data do Pedido</th>
                                            <th style="width: 130px;">Cliente</th>
                                            <th style="width: 130px;">Usuário</th>
                                            <th style="width: 150px;">Endereço de Entrega</th>
                                            <th style="width: 150px;">Data de Entrega</th>
                                            <th style="width: 70px;">Ações</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $pedidos->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['id_ped'] ?></td>
                                            <td><?= $row['data_ped'] ?></td>
                                            <td><?= htmlspecialchars($row['nome_cli']) ?></td>
                                            <td><?= htmlspecialchars($row['nome_usu']) ?></td>
                                            <td><?= $row['endereco_entrega'] ?></td>
                                            <td><?= $row['data_entrega_ped'] ?></td>
                                            <td>
                                                <a href="edit/edit_ped.php?id=<?= $row['id_ped'] ?>" class="btn btn-primary">Edit</a>
                                            </td> 
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>