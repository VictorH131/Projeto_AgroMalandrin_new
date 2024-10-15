<?php
    include_once '../secure/verifica_login/session.php';  // Verificando se você está logado
    include_once "../secure/includes/dbconnect.php";

    $erro = '';
    $success = '';

    // Inserir/Atualizar Cliente
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["nome_cli"], $_POST["documento_cli"], $_POST["tipo_do_documento_cli"], $_POST["data_nascimento"], $_POST["email_cli"], $_POST["rua"], $_POST["bairro"], $_POST["cidade"], $_POST["cep"], $_POST["telefone_cli"], $_POST["uf"])) {
            if (empty($_POST["nome_cli"]) || empty($_POST["documento_cli"]) || empty($_POST["tipo_do_documento_cli"]) || empty($_POST["data_nascimento"]) || empty($_POST["email_cli"]) || empty($_POST["rua"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["cep"]) || empty($_POST["telefone_cli"]) || empty($_POST["uf"])) {
                $erro = "Todos os campos obrigatórios devem ser preenchidos.";
            } else {
                $id_cli = isset($_POST["id_cli"]) ? $_POST["id_cli"] : -1;
                $nome_cli = $_POST["nome_cli"];
                $documento_cli = $_POST["documento_cli"];
                $tipo_do_documento_cli = $_POST["tipo_do_documento_cli"];
                $data_nascimento = $_POST["data_nascimento"];
                $data_cadastro_cli = date('Y-m-d H:i:s'); // Setando a data e hora atual
                $email_cli = $_POST["email_cli"];
                $rua = $_POST["rua"];
                $bairro = $_POST["bairro"];
                $cidade = $_POST["cidade"];
                $numero = $_POST["numero"];
                $cep = $_POST["cep"];
                $telefone_cli = $_POST["telefone_cli"];
                $uf = $_POST["uf"];
                $status_cli = "ativo"; // Definindo status inicial como 'ativo'

                if ($id_cli == -1) { // Inserir novo cliente
                    $stmt = $con->prepare("INSERT INTO Cliente (data_cadastro_cli, nome_cli, documento_cli, tipo_do_documento_cli, data_nascimento, email_cli, rua, bairro, cidade, cep, telefone_cli, numero, uf, status_cli) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    $stmt->bind_param("ssssssssssssss", $data_cadastro_cli, $nome_cli, $documento_cli, $tipo_do_documento_cli, $data_nascimento, $email_cli, $rua, $bairro, $cidade, $cep, $telefone_cli, $numero, $uf, $status_cli);
                
                    if ($stmt->execute()) {
                        $success = "Cliente cadastrado com sucesso.";
                    } else {
                        $erro = "Erro ao cadastrar cliente: " . $stmt->error;
                    }
                }
                
            }
        } else {
            $erro = "Todos os campos obrigatórios devem ser preenchidos.";
        }
    }

    // Desabilitar Cliente
    if (isset($_GET["id_cli"]) && is_numeric($_GET["id_cli"]) && isset($_GET["del"])) {
        $id_cli = (int) $_GET["id_cli"];
        $stmt = $con->prepare("UPDATE Cliente SET status_cli = 'desabilitado' WHERE id_cli = ?");
        $stmt->bind_param('i', $id_cli);
        if ($stmt->execute()) {
            $success = "Cliente desabilitado com sucesso.";
        } else {
            $erro = "Erro ao desabilitar cliente: " . $stmt->error;
        }
        
    }

    // Consulta para buscar todos os fornecedores ativos
    $sql = "SELECT * FROM Cliente WHERE status_cli = 'ativo'";
    $clientes = $con->query($sql);

    // Listar Clientes
    $result = $con->query("SELECT * FROM Cliente WHERE status_cli = 'ativo'");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>+Serviços | Agro Malandrin</title>
    <!-- colocando o icone da pagina-->
    <link rel="shortcut icon" href="../multimidia/icones/planta.png" type="image/x-icon">
    <link rel="stylesheet" href="styles/forms.css">
</head>
<?php
    include_once "includes/nav.php";
?>
<body>

    <div class="container">
        <h1>Cadastro de Clientes</h1>

            <?php if (!empty($erro)): ?>
                <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <p style="color: green;"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>

    <!-- Formulário para adicionar ou editar cliente -->
                <form action="cliente.php" method="POST">
                    <input type="hidden" name="id_cli" value="<?= isset($_POST['id_cli']) ? (int) $_POST['id_cli'] : -1 ?>">
            
                        <label for="nome_cli">Nome do Cliente:</label>
                        <input type="text" name="nome_cli" value="<?= isset($_POST['nome_cli']) ? htmlspecialchars($_POST['nome_cli']) : '' ?>" required>
                
                        <label for="tipo_do_documento_cli">Tipo de Documento:</label>
                            <select name="tipo_do_documento_cli" required>
                                <div class="tipo_documento">
                                <optgroup label="Documento">
                                    <option value="invalido">SELECIONE</option>
                                    <option value="cpf" <?= (isset($_POST['tipo_do_documento_cli']) && $_POST['tipo_do_documento_cli'] === 'cpf') ? 'selected' : '' ?>>CPF</option>
                                    <option value="rg" <?= (isset($_POST['tipo_do_documento_cli']) && $_POST['tipo_do_documento_cli'] === 'rg') ? 'selected' : '' ?>>RG</option>
                                    </div>
                                </optgroup>
                            </select>

                        <label for="documento_cli">Documento:</label>
                        <input type="text" name="documento_cli"
                        value="<?= isset($_POST['documento_cli']) ? htmlspecialchars($_POST['documento_cli']) : '' ?>"
                        required>

                    <label for="data_nascimento">Data de Nascimento:</label>
                    <input type="date" name="data_nascimento"
                        value="<?= isset($_POST['data_nascimento']) ? htmlspecialchars($_POST['data_nascimento']) : '' ?>"
                        required>

                    <label for="email_cli">Email:</label>
                    <input type="email" name="email_cli"
                        value="<?= isset($_POST['email_cli']) ? htmlspecialchars($_POST['email_cli']) : '' ?>" required>

                    <label for="cep">CEP:</label>
                    <input type="text" name="cep" value="<?= isset($_POST['cep']) ? htmlspecialchars($_POST['cep']) : '' ?>"
                        required>

                    <label for="rua">Rua:</label>
                    <input type="text" name="rua" value="<?= isset($_POST['rua']) ? htmlspecialchars($_POST['rua']) : '' ?>"
                        required>

                    <label for="bairro">Bairro:</label>
                    <input type="text" name="bairro"
                        value="<?= isset($_POST['bairro']) ? htmlspecialchars($_POST['bairro']) : '' ?>" required>

                    <label for="cidade">Cidade:</label>
                    <input type="text" name="cidade"
                        value="<?= isset($_POST['cidade']) ? htmlspecialchars($_POST['cidade']) : '' ?>" required>

                    <label for="numero">Numero:</label>
                    <input type="number" name="numero" min="0"
                        value="<?= isset($_POST['numero']) ? htmlspecialchars($_POST['numero']) : '' ?>" required>

                    <label for="telefone_cli">Telefone:</label>
                    <input type="text" name="telefone_cli"
                        value="<?= isset($_POST['telefone_cli']) ? htmlspecialchars($_POST['telefone_cli']) : '' ?>"
                        required>

                    <label for="uf">UF:</label>
                    <select name="uf" required>
                        <option value="invalido">SELECIONE</option>
                        <?php
                        $ufs = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
                        foreach ($ufs as $uf) {
                            echo '<option value="' . $uf . '" ' . (isset($_POST['uf']) && $_POST['uf'] === $uf ? 'selected' : '') . '>' . $uf . '</option>';
                        }
                        ?>
                    </select><br><br>

                    <button type="submit"><?= isset($_POST['id_cli']) && $_POST['id_cli'] != -1 ? 'Salvar' : 'Cadastrar' ?></button>
                </form>
                
                <hr>
    <!-- Listar clientes -->
    <h2>Clientes Ativos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Documento</th>
                <th>Email</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["id_cli"] ?></td>
                    <td><?= $row["nome_cli"] ?></td>
                    <td><?= $row["documento_cli"] ?></td>
                    <td><?= $row["email_cli"] ?></td>
                    <td><?= $row['status_cli'] ?></td>
                    
                    <td>
                        <!-- modificar o editar logo abaixo - 09/10/24 -->
                        <a href="editar_cliente.php?id_cli=<?= $row["id_cli"] ?>">Editar</a> |
                        <a href="cliente.php?id_cli=<?= $row["id_cli"] ?>&del=true" onclick="return confirm('Tem certeza que deseja desabilitar este cliente?')">Desabilitar</a>
                       
                       
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>

</html>