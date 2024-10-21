<?php 
require_once '../includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);
$success = '';
$aviso = '';

// Verificar o ID do fornecedor
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID do fornecedor não fornecido. Verifique a URL.");
}

// Verificar se a requisição é um update
if (isset($_POST['update'])) { 
    // Verifica se todos os campos obrigatórios foram preenchidos
    if (empty($_POST["nome_for"]) || empty($_POST["documento_for"]) || empty($_POST["tipo_do_documento_for"]) || empty($_POST["email_for"]) || empty($_POST["rua"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["cep"]) || empty($_POST["telefone_for"]) || empty($_POST["uf"])) {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        // Captura os dados do formulário
        $nome_for = $_POST["nome_for"];
        $documento_for = $_POST["documento_for"];
        $tipo_do_documento_for = $_POST["tipo_do_documento_for"];
        $email_for = $_POST["email_for"];
        $telefone_for = $_POST["telefone_for"];
        $celular_for = $_POST["celular_for"] ?? null;
        $uf = $_POST["uf"];
        $cidade = $_POST["cidade"];
        $bairro = $_POST["bairro"];
        $rua = $_POST["rua"];
        $cep = $_POST["cep"];
        $data_cadastro_for = date('Y-m-d'); // data de cadastro será a data atual

        // Validação de CPF, se necessário
        if ($tipo_do_documento_for === 'cpf' && !validarCPF($documento_for)) {
            $aviso = "CPF inválido.";
        } else {
            // Atualiza os dados no banco de dados
            $sql = "UPDATE Fornecedor SET 
                        nome_for = ?, 
                        email_for = ?, 
                        tipo_do_documento_for = ?, 
                        documento_for = ?, 
                        data_cadastro_for = ?, 
                        telefone_for = ?, 
                        celular_for = ?, 
                        rua = ?, 
                        bairro = ?, 
                        cidade = ?, 
                        uf = ?, 
                        cep = ? 
                    WHERE id_for = ?";

            // Prepara e executa a query
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssssi", 
                $nome_for, $email_for, $tipo_do_documento_for, $documento_for, 
                $data_cadastro_for, $telefone_for, $celular_for, $rua, 
                $bairro, $cidade, $uf, $cep, $id);

            if ($stmt->execute()) {
                $success = "Fornecedor atualizado com sucesso.";
            } else {
                $aviso = "Erro ao atualizar o fornecedor: " . $stmt->error;
            }
        }
    }
}

// Obter os dados do fornecedor
$sql = "SELECT * FROM Fornecedor WHERE id_for = {$id}";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "<div class='alert alert-danger'>Fornecedor não encontrado.</div>";
    exit; // Interromper o código caso não encontre o fornecedor
}

// Função para validar CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf); // Remove caracteres não numéricos
    if (strlen($cpf) != 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false; // CPF inválido
    }

    // Cálculo do primeiro dígito verificador
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += intval($cpf[$i]) * (10 - $i);
    }
    $primeiroDigito = 11 - ($soma % 11);
    if ($primeiroDigito >= 10) {
        $primeiroDigito = 0;
    }
    if (intval($cpf[9]) != $primeiroDigito) {
        return false; // CPF inválido
    }

    // Cálculo do segundo dígito verificador
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += intval($cpf[$i]) * (11 - $i);
    }
    $segundoDigito = 11 - ($soma % 11);
    if ($segundoDigito >= 10) {
        $segundoDigito = 0;
    }
    if (intval($cpf[10]) != $segundoDigito) {
        return false; // CPF inválido
    }

    return true; // CPF válido
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar - Fornecedores | Agro Malandrin</title>
     
    <!-- colocando o icone da pagina-->
    <link rel="shortcut icon" href="../../multimidia/icones/planta.png" type="image/x-icon">

    <!-- linkando ao Bootstrap v5.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-9 mx-auto">
                <div class="box">
                    <a href="../fornecedor.php" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="box">
                
                <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Modificar Fornecedor</h3>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php elseif ($aviso): ?>
                    <div class="alert alert-danger"><?php echo $aviso; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <input type="hidden" value="<?php echo $row['id_for'];?>" name="id_for">

                    <label for="nome_for">Nome:</label>
                    <input type="text" id="nome_for" name="nome_for" value="<?php echo htmlspecialchars($row['nome_for']);?>" required class="form-control"><br>

                    <label for="email_for">E-mail:</label>
                    <input type="email" id="email_for" name="email_for" value="<?php echo htmlspecialchars($row['email_for']);?>" required class="form-control"><br>

                    <label for="telefone_for">Telefone:</label>
                    <input type="text" id="telefone_for" name="telefone_for" value="<?php echo htmlspecialchars($row['telefone_for']);?>" class="form-control"><br>

                    <label for="celular_for">Celular:</label>
                    <input type="text" id="celular_for" name="celular_for" value="<?php echo htmlspecialchars($row['celular_for']);?>" class="form-control"><br>

                    <label for="tipo_do_documento_for">Tipo de Documento:</label>
                    <select name="tipo_do_documento_for" id="tipo_do_documento_for" class="form-control" required> 
                        <option value="cpf" <?= ($row['tipo_do_documento_for'] == 'cpf') ? 'selected' : '' ?>>CPF</option>
                        <option value="rg" <?= ($row['tipo_do_documento_for'] == 'rg') ? 'selected' : '' ?>>RG</option>
                    </select><br>

                    <label for="documento_for">Documento:</label>
                    <input type="text" id="documento_for" name="documento_for" value="<?php echo htmlspecialchars($row['documento_for']);?>" required class="form-control"><br>

                    <label for="rua">Rua:</label>
                    <input type="text" id="rua" name="rua" value="<?php echo htmlspecialchars($row['rua']);?>" required class="form-control"><br>

                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" value="<?php echo htmlspecialchars($row['bairro']);?>" required class="form-control"><br>

                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($row['cidade']);?>" required class="form-control                    "><br>

                    <label for="uf">Estado:</label><br>
                    <select id="uf" name="uf" class="form-control" required>
                        <option value="">Selecione</option>
                        <option value="SP" <?= ($row['uf'] == 'SP') ? 'selected' : '' ?>>São Paulo</option>
                        <option value="MG" <?= ($row['uf'] == 'MG') ? 'selected' : '' ?>>Minas Gerais</option>
                        <option value="RJ" <?= ($row['uf'] == 'RJ') ? 'selected' : '' ?>>Rio de Janeiro</option>
                        <option value="RS" <?= ($row['uf'] == 'RS') ? 'selected' : '' ?>>Rio Grande do Sul</option>
                        <option value="BA" <?= ($row['uf'] == 'BA') ? 'selected' : '' ?>>Bahia</option>
                        <option value="PR" <?= ($row['uf'] == 'PR') ? 'selected' : '' ?>>Paraná</option>
                        <option value="SC" <?= ($row['uf'] == 'SC') ? 'selected' : '' ?>>Santa Catarina</option>
                        <option value="DF" <?= ($row['uf'] == 'DF') ? 'selected' : '' ?>>Distrito Federal</option>
                        <option value="CE" <?= ($row['uf'] == 'CE') ? 'selected' : '' ?>>Ceará</option>
                        <option value="PE" <?= ($row['uf'] == 'PE') ? 'selected' : '' ?>>Pernambuco</option>
                        <option value="ES" <?= ($row['uf'] == 'ES') ? 'selected' : '' ?>>Espírito Santo</option>
                        <option value="MA" <?= ($row['uf'] == 'MA') ? 'selected' : '' ?>>Maranhão</option>
                        <option value="GO" <?= ($row['uf'] == 'GO') ? 'selected' : '' ?>>Goiás</option>
                        <option value="MT" <?= ($row['uf'] == 'MT') ? 'selected' : '' ?>>Mato Grosso</option>
                        <option value="MS" <?= ($row['uf'] == 'MS') ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                        <option value="AM" <?= ($row['uf'] == 'AM') ? 'selected' : '' ?>>Amazonas</option>
                        <option value="PA" <?= ($row['uf'] == 'PA') ? 'selected' : '' ?>>Pará</option>
                        <option value="AP" <?= ($row['uf'] == 'AP') ? 'selected' : '' ?>>Amapá</option>
                        <option value="TO" <?= ($row['uf'] == 'TO') ? 'selected' : '' ?>>Tocantins</option>
                        <option value="RO" <?= ($row['uf'] == 'RO') ? 'selected' : '' ?>>Rondônia</option>
                        <option value="AC" <?= ($row['uf'] == 'AC') ? 'selected' : '' ?>>Acre</option>
                        <option value="RR" <?= ($row['uf'] == 'RR') ? 'selected' : '' ?>>Roraima</option>
                        <option value="AL" <?= ($row['uf'] == 'AL') ? 'selected' : '' ?>>Alagoas</option>
                    </select><br>

                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($row['cep']);?>" required class="form-control"><br>

                    <button type="submit" name="update" class="btn btn-primary">Atualizar</button>
                </form>
                </div>
            </div>
        </div>
    </div>

    <!-- linkando ao Bootstrap JS v5.1 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-3yBfE9q1V1/Z3FbW8mzzZfCVU4O9D5FEnkJt1ZoX6z6O5M0MO5m2/4t2O8HvBlRl" crossorigin="anonymous"></script>
</body>
</html>

