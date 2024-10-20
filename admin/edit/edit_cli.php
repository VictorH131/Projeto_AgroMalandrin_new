<?php
require_once '../includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = '';
$aviso = '';

// Verificar se o formulário foi enviado
if (isset($_POST['update'])) { 
    // Verifica se todos os campos obrigatórios foram preenchidos
    if (empty($_POST["nome_cli"]) || empty($_POST["documento_cli"]) || empty($_POST["tipo_documento"]) || empty($_POST["data_nascimento"]) || empty($_POST["email_cli"]) || empty($_POST["rua"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["cep"]) || empty($_POST["telefone_cli"]) || empty($_POST["uf"])) {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        // Captura os dados do formulário
        $nome_cli = $_POST["nome_cli"];
        $nome_social = $_POST["nome_social"] ?? null;
        $documento_cli = $_POST["documento_cli"];
        $tipo_documento = $_POST["tipo_documento"];
        $data_nascimento = $_POST["data_nascimento"];
        $email_cli = $_POST["email_cli"];
        $rua = $_POST["rua"];
        $bairro = $_POST["bairro"];
        $cidade = $_POST["cidade"];
        $numero = $_POST["numero"];
        $cep = $_POST["cep"];
        $telefone_cli = $_POST["telefone_cli"];
        $celular_cli = $_POST["celular_cli"] ?? null;
        $uf = $_POST["uf"];
        $complemento = $_POST["complemento"] ?? null;

        // Atualizar cliente no banco de dados
        $sql = "UPDATE Cliente SET 
                    nome_cli = ?, 
                    nome_social = ?, 
                    email_cli = ?, 
                    tipo_do_documento_cli = ?, 
                    documento_cli = ?, 
                    data_nascimento = ?, 
                    telefone_cli = ?, 
                    celular_cli = ?, 
                    rua = ?, 
                    numero = ?, 
                    bairro = ?, 
                    complemento = ?, 
                    cidade = ?, 
                    uf = ?, 
                    cep = ? 
                WHERE id_cli = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssssssi", 
            $nome_cli, $nome_social, $email_cli, $tipo_documento, $documento_cli, $data_nascimento, 
            $telefone_cli, $celular_cli, $rua, $numero, $bairro, $complemento, $cidade, $uf, $cep, $_POST['id_cli']);

        if ($stmt->execute()) {
            $success = "Cliente atualizado com sucesso.";
        } else {
            $aviso = "Erro ao atualizar cliente: " . $stmt->error;
        }
    }
}

// Verificar o ID do cliente e obter os dados do banco de dados
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM Cliente WHERE id_cli = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "<div class='alert alert-danger'>Cliente não encontrado.</div>";
    exit; // Interromper o código caso não encontre o cliente
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar - Cliente | Agro Malandrin</title>
    <link rel="shortcut icon" href="../../multimidia/icones/planta.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <?php if (!empty($aviso)): ?>
            <div class='alert alert-danger'><?= htmlspecialchars($aviso) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class='alert alert-success'><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-9 mx-auto">
                <div class="box">
                    <a href="../clientes.php" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box">
                    <h3>Modificar Cliente</h3>
                    <form action="" method="POST">
                        <input type="hidden" value="<?php echo $row['id_cli'];?>" name="id_cli">

                        <label for="nome_cli">Nome:</label>
                        <input type="text" id="nome_cli" name="nome_cli" value="<?php echo $row['nome_cli'];?>" required class="form-control"><br>

                        <label for="nome_social">Nome Social:</label>
                        <input type="text" id="nome_social" name="nome_social" value="<?php echo $row['nome_social'];?>" class="form-control"><br>

                        <label for="email_cli">E-mail:</label>
                        <input type="email" id="email_cli" name="email_cli" value="<?php echo $row['email_cli'];?>" required class="form-control"><br>

                        <label for="telefone_cli">Telefone:</label>
                        <input type="text" id="telefone_cli" name="telefone_cli" value="<?php echo $row['telefone_cli'];?>" class="form-control"><br>

                        <label for="celular_cli">Celular:</label>
                        <input type="text" id="celular_cli" name="celular_cli" value="<?php echo $row['celular_cli'];?>" class="form-control"><br>

                        <label for="data_nascimento">Data de Nascimento:</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo $row['data_nascimento'];?>" required class="form-control"><br>

                        <label for="tipo_documento">Tipo de Documento:</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-control" required> 
                            <option value="cpf" <?= ($row['tipo_do_documento_cli'] == 'cpf') ? 'selected' : '' ?>>CPF</option>
                            <option value="rg" <?= ($row['tipo_do_documento_cli'] == 'rg') ? 'selected' : '' ?>>RG</option>
                        </select><br>

                        <label for="documento_cli">Documento:</label>
                        <input type="text" id="documento_cli" name="documento_cli" value="<?php echo $row['documento_cli'];?>" required class="form-control"><br>

                        <label for="rua">Rua:</label>
                        <input type="text" id="rua" name="rua" value="<?php echo $row['rua'];?>" required class="form-control"><br>

                        <label for="numero">Número:</label>
                        <input type="text" id="numero" name="numero" value="<?php echo $row['numero'];?>" required class="form-control"><br>

                        <label for="bairro">Bairro:</label>
                        <input type="text" id="bairro" name="bairro" value="<?php echo $row['bairro'];?>" required class="form-control"><br>

                        <label for="cidade">Cidade:</label>
                        <input type="text" id="cidade" name="cidade" value="<?php echo $row['cidade'];?>" required class="form-control"><br>

                        <label for="uf">Estado:</label><br>
                        <select id="uf" name="uf" class="form-control" required>
                            <option value="">Selecione</option>
                            <option value="AC" <?= ($row['uf'] == 'AC') ? 'selected' : '' ?>>Acre</option>
                            <option value="AL" <?= ($row['uf'] == 'AL') ? 'selected' : '' ?>>Alagoas</option>
                            <option value="AP" <?= ($row['uf'] == 'AP') ? 'selected' : '' ?>>Amapá</option>
                            <option value="AM" <?= ($row['uf'] == 'AM') ? 'selected' : '' ?>>Amazonas</option>
                            <option value="BA" <?= ($row['uf'] == 'BA') ? 'selected' : '' ?>>Bahia</option>
                            <option value="CE" <?= ($row['uf'] == 'CE') ? 'selected' : '' ?>>Ceará</option>
                            <option value="DF" <?= ($row['uf'] == 'DF') ? 'selected' : '' ?>>Distrito Federal</option>
                            <option value="ES" <?= ($row['uf'] == 'ES') ? 'selected' : '' ?>>Espírito Santo</option>
                            <option value="GO" <?= ($row['uf'] == 'GO') ? 'selected' : '' ?>>Goiás</option>
                            <option value="MA" <?= ($row['uf'] == 'MA') ? 'selected' : '' ?>>Maranhão</option>
                            <option value="MT" <?= ($row['uf'] == 'MT') ? 'selected' : '' ?>>Mato Grosso</option>
                            <option value="MS" <?= ($row['uf'] == 'MS') ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                            <option value="MG" <?= ($row['uf'] == 'MG') ? 'selected' : '' ?>>Minas Gerais</option>
                            <option value="PA" <?= ($row['uf'] == 'PA') ? 'selected' : '' ?>>Pará</option>
                            <option value="PB" <?= ($row['uf'] == 'PB') ? 'selected' : '' ?>>Paraíba</option>
                            <option value="PR" <?= ($row['uf'] == 'PR') ? 'selected' : '' ?>>Paraná</option>
                            <option value="PE" <?= ($row['uf'] == 'PE') ? 'selected' : '' ?>>Pernambuco</option>
                            <option value="PI" <?= ($row['uf'] == 'PI') ? 'selected' : '' ?>>Piauí</option>
                            <option value="RJ" <?= ($row['uf'] == 'RJ') ? 'selected' : '' ?>>Rio de Janeiro</option>
                            <option value="RN" <?= ($row['uf'] == 'RN') ? 'selected' : '' ?>>Rio Grande do Norte</option>
                            <option value="RS" <?= ($row['uf'] == 'RS') ? 'selected' : '' ?>>Rio Grande do Sul</option>
                            <option value="RO" <?= ($row['uf'] == 'RO') ? 'selected' : '' ?>>Rondônia</option>
                            <option value="RR" <?= ($row['uf'] == 'RR') ? 'selected' : '' ?>>Roraima</option>
                            <option value="SC" <?= ($row['uf'] == 'SC') ? 'selected' : '' ?>>Santa Catarina</option>
                            <option value="SP" <?= ($row['uf'] == 'SP') ? 'selected' : '' ?>>São Paulo</option>
                            <option value="SE" <?= ($row['uf'] == 'SE') ? 'selected' : '' ?>>Sergipe</option>
                            <option value="TO" <?= ($row['uf'] == 'TO') ? 'selected' : '' ?>>Tocantins</option>
                        </select><br>

                        <label for="cep">CEP:</label>
                        <input type="text" id="cep" name="cep" value="<?php echo $row['cep'];?>" required class="form-control"><br>

                        <label for="complemento">Complemento:</label>
                        <input type="text" id="complemento" name="complemento" value="<?php echo $row['complemento'];?>" class="form-control"><br>

                        <button type="submit" name="update" class="btn btn-success">Atualizar Cliente</button>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
