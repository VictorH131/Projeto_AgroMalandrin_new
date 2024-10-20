<?php 
require_once '../includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);
$success = '';
$aviso = '';

if (isset($_POST['update'])) { 
    // Verifica se todos os campos obrigatórios foram preenchidos
    if (empty($_POST["nome_for"]) || empty($_POST["documento_for"]) || empty($_POST["tipo_do_documento_for"]) || empty($_POST["data_cadastro_for"]) || empty($_POST["email_for"]) || empty($_POST["rua"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["cep"]) || empty($_POST["telefone_for"]) || empty($_POST["uf"])) {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        // Captura os dados do formulário
        $nome_for = $_POST["nome_for"];
        $documento_for = $_POST["documento_for"];
        $tipo_do_documento_for = $_POST["tipo_do_documento_for"];
        $data_cadastro_for = $_POST["data_cadastro_for"];
        $email_for = $_POST["email_for"];
        $rua = $_POST["rua"];
        $bairro = $_POST["bairro"];
        $cidade = $_POST["cidade"];
        $numero = $_POST["numero"];
        $cep = $_POST["cep"];
        $telefone_for = $_POST["telefone_for"];
        $celular_for = $_POST["celular_for"] ?? null;
        $uf = $_POST["uf"];
        $complemento = $_POST["complemento"] ?? null;

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
                        numero = ?, 
                        bairro = ?, 
                        complemento = ?, 
                        cidade = ?, 
                        uf = ?, 
                        cep = ?
                    WHERE id_for = ?";

            // Prepara e executa a query
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssssssi", 
                $nome_for, $email_for, $tipo_do_documento_for, $documento_for, $data_cadastro_for, 
                $telefone_for, $celular_for, $rua, $numero, $bairro, $complemento, $cidade, $uf, $cep, $_POST['id_for']);

            if ($stmt->execute()) {
                $success = "Fornecedor atualizado com sucesso.";
            } else {
                $aviso = "Erro ao atualizar o fornecedor: " . $stmt->error;
            }
        }
    }
}

// Verificar o ID do fornecedor e obter os dados do banco de dados
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
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

                    <label for="data_cadastro_for">Data de Cadastro:</label>
                    <input type="date" id="data_cadastro_for" name="data_cadastro_for" value="<?php echo htmlspecialchars($row['data_cadastro_for']);?>" required class="form-control"><br>

                    <label for="tipo_do_documento_for">Tipo de Documento:</label>
                    <select name="tipo_do_documento_for" id="tipo_do_documento_for" class="form-control" required> 
                        <option value="cpf" <?= ($row['tipo_do_documento_for'] == 'cpf') ? 'selected' : '' ?>>CPF</option>
                        <option value="rg" <?= ($row['tipo_do_documento_for'] == 'rg') ? 'selected' : '' ?>>RG</option>
                    </select><br>

                    <label for="documento_for">Documento:</label>
                    <input type="text" id="documento_for" name="documento_for" value="<?php echo htmlspecialchars($row['documento_for']);?>" required class="form-control"><br>

                    <label for="rua">Rua:</label>
                    <input type="text" id="rua" name="rua" value="<?php echo htmlspecialchars($row['rua']);?>" required class="form-control"><br>

                    <label for="numero">Número:</label>
                    <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($row['numero']);?>" required class="form-control"><br>

                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" value="<?php echo htmlspecialchars($row['bairro']);?>" required class="form-control"><br>

                    <label for="complemento">Complemento:</label>
                    <input type="text" id="complemento" name="complemento" value="<?php echo htmlspecialchars($row['complemento']);?>" class="form-control"><br>

                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($row['cidade']);?>" required class="form-control"><br>

                    <label for="uf">Estado (UF):</label>
                    <input type="text" id="uf" name="uf" value="<?php echo htmlspecialchars($row['uf']);?>" required class="form-control"><br>

                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($row['cep']);?>" required class="form-control"><br>

                    <button type="submit" name="update" class="btn btn-success">Atualizar Fornecedor</button>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>


</body>
</html>
