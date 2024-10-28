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
                
                <h3 style="margin-left: -1%;"><i class="glyphicon glyphicon-plus"></i>&nbsp;Modificar Fornecedor</h3>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php elseif ($aviso): ?>
                    <div class="alert alert-danger"><?php echo $aviso; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <input type="hidden" value="<?php echo $row['id_for'];?>" name="id_for">

                    <label for="nome_for">Nome:</label>
                    <input type="text" id="nome_for" name="nome_for" style="width: 622px;" value="<?php echo htmlspecialchars($row['nome_for']);?>" required class="form-control"><br>

                    <label for="email_for">E-mail:</label>
                    <input type="email" id="email_for" name="email_for" style="width: 622px;" value="<?php echo htmlspecialchars($row['email_for']);?>" required class="form-control"><br>

                    <div class="d-flex mt-0">
                        <div class="row">
                            <div class="col">
                                <label for="telefone_for">Telefone:</label>
                                <input type="text" id="telefone_for" name="telefone_for" style="width: 300px;" value="<?php echo htmlspecialchars($row['telefone_for']);?>" class="form-control"><br>
                            </div>

                            <div class="col">
                                <label for="celular_for" style="position: 10%;">Celular:</label>
                                <input type="text" id="celular_for" name="celular_for" style="width: 300px; position: 10%;" value="<?php echo htmlspecialchars($row['celular_for']);?>" class="form-control"><br>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex mt-0">
                        <div class="row">
                            <div class="col">
                                <label for="tipo_do_documento_for">Tipo de Documento:</label>
                                <select name="tipo_do_documento_for" id="tipo_do_documento_for" class="form-control" required style="width: 300px;"> 
                                    <option value="cpf" <?= ($row['tipo_do_documento_for'] == 'cpf') ? 'selected' : '' ?>>CPF</option>
                                    <option value="rg" <?= ($row['tipo_do_documento_for'] == 'rg') ? 'selected' : '' ?>>RG</option>
                                </select><br>
                            </div>

                            <div class="col">
                                <label for="documento_for" style="position: 10%;">Documento:</label>
                                <input type="text" id="documento_for" name="documento_for" style="width: 300px; position: 10%;" value="<?php echo htmlspecialchars($row['documento_for']);?>" required class="form-control"><br>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex mt-0">
                        <div class="row">
                            <div class="col">
                                <label for="uf">Estado:</label>
                                <select id="uf" name="uf"  class="form-control" required style="width: 300px;">
                                    <option>Selecione</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="BA">Bahia</option>
                                    <option value="PR">Paraná</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="CE">Ceará</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="PA">Pará</option>
                                    <option value="AP">Amapá</option>
                                    <option value="TO">Tocantins</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="AC">Acre</option>
                                    <option value="RR">Roraima</option>
                                    <option value="AL">Alagoas</option>
                                </select>
                            </div>

                            <div class="col">
                                <label for="cidade" style="position: 10%;">Cidade:</label>    
                                <input type="text" id="cidade" name="cidade" required class="form-control" style="width: 300px; position: 10%;" value="<?= htmlspecialchars($_POST['cidade'] ?? '') ?>"><br>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex mt-0">
                        <div class="row">
                            <div class="col">
                                <label for="bairro">Bairro:</label>
                                <input type="text" id="bairro" name="bairro" required class="form-control" style="width: 300px;" value="<?= htmlspecialchars($_POST['bairro'] ?? '') ?>">
                            </div>

                            <div class="col">
                                <label for="cep" style="position: 10%;">CEP:</label>
                                <input type="text" id="cep" name="cep" required class="form-control" style="width: 300px; position: 10%;" value="<?= htmlspecialchars($_POST['cep'] ?? '') ?>"><br>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex mt-0">
                        <div class="row">
                            <div class="col">
                                <label for="rua">Rua:</label>
                                <input type="text" id="rua" name="rua" required class="form-control" style="width: 300px;" value="<?= htmlspecialchars($_POST['rua'] ?? '') ?>">
                            </div>

                            <div class="col">
                                <label for="numero" style="position: 10%;">Número:</label>
                                <input type="text" id="numero" name="numero" class="form-control" style="width: 300px; position: 10%;" value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>"><br>
                            </div>
                        </div>
                    </div>

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

