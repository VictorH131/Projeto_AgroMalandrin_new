<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração /
 
error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = '';
$aviso = '';


if (isset($_POST['addnew'])) {
    // Verifica se todos os campos necessários foram enviados
    if (isset($_POST["nome_usu"], $_POST["email_usu"], $_POST["data_nascimento"], $_POST["documento_usu"], $_POST["cep"], $_POST["rua"], $_POST["numero"], $_POST["bairro"], $_POST["cidade"], $_POST["uf"], $_POST["senha"])) {
        // Verifica se os campos obrigatórios estão vazios
        if (empty($_POST["noome_usu"]) || empty($_POST["email_usu"]) || empty($_POST["data_nascimento"]) || empty($_POST["documento_usu"]) || empty($_POST["cep"]) || empty($_POST["rua"]) || empty($_POST["numero"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["uf"]) || empty($_POST["senha"])) {
            $aviso = "Todos os campos são obrigatórios.";
        } else {
            // Captura os dados do formulário
            $nome_usu = $_POST["nome_usu"];
            $email_usu = $_POST["email_usu"];
            $documento_usu = $_POST["documento_usu"];
            $data_nascimento = $_POST["data_nascimento"];
            $cep = $_POST["cep"];
            $rua = $_POST["rua"];
            $numero = $_POST["numero"];
            $bairro = $_POST["bairro"];
            $cidade = $_POST["cidade"];
            $uf = $_POST["uf"];
            $senha = password_hash($_POST["senha"], PASSWORD_BCRYPT); // Criptografa a senha

            // Validação do CPF
            if ($_POST["tipo_documento"] === 'cpf' && !validarCPF($documento_usu)) {
                $aviso = "CPF inválido.";
            } else {
                // Preparar a inserção
                $stmt = $conn->prepare("INSERT INTO Usuario (nome_usu, email_usu, documento_usu, data_nascimento, cep, rua, numero, bairro, cidade, uf, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssss", $nome_usu, $email_usu, $documento_usu, $data_nascimento, $cep, $rua, $numero, $bairro, $cidade, $uf, $senha);
                
                // Executar a inserção
                if ($stmt->execute()) {
                    $success = "Usuário cadastrado com sucesso.";
                } else {
                    $aviso = "Erro ao cadastrar usuário: " . $stmt->error;
                }
            }
        }
    } else {
        $aviso = "Erro: Campo faltante detectado.";
    }
}

// Função para validar CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }

    // Cálculo do primeiro dígito verificador
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += intval($cpf[$i]) * (10 - $i);
    }
    $primeiroDigito = 11 - ($soma % 11);
    $primeiroDigito = ($primeiroDigito >= 10) ? 0 : $primeiroDigito;
    if (intval($cpf[9]) != $primeiroDigito) {
        return false;
    }

    // Cálculo do segundo dígito verificador
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += intval($cpf[$i]) * (11 - $i);
    }
    $segundoDigito = 11 - ($soma % 11);
    $segundoDigito = ($segundoDigito >= 10) ? 0 : $segundoDigito;
    return (intval($cpf[10]) == $segundoDigito);
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário | Agro Malandrin</title>
    
</head>       

<body>
    <div class="container justify-content-center">
        <div class="row"> 
            <div class="col-md-6 mx-auto"> 
                <div class="box"> 
                    <h3>Cadastro de Usuários</h3><br>

                    <!-- Exibir mensagens de sucesso ou problema-->
                    <?php if (!empty($success)): ?>
                        <div class='alert alert-success'><?= $success ?></div>
                    <?php endif; ?>
                    <?php if (!empty($aviso)): ?>
                        <div class='alert alert-warning'><?= $aviso ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <label for="user_name">Nome:</label>
                        <input type="text" id="user_name" name="user_name" required class="form-control"><br>

                        <label for="email_usu">E-mail:</label>
                        <input type="email" id="email_usu" name="email_usu" required class="form-control"><br>

                        <label for="tipo_documento_usu">Tipo de Documento:</label>
                        <select name="tipo_documento_usu" id="tipo_documento_usu" class="form-control" required>
                            <option value="invalido">Selecione</option>
                            <option value="cpf">CPF</option>
                            <option value="rg">RG</option>
                        </select><br>

                        <label for="documento_usuario">Documento:</label>
                        <input type="text" id="documento_usuario" name="documento_usuario" required class="form-control"><br>

                        <label for="data_nascimento">Data de Nascimento:</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" required class="form-control"><br>

                        <label for="cep">CEP:</label>
                        <input type="text" id="cep" name="cep" required class="form-control"><br>

                        <label for="rua">Rua:</label>
                        <input type="text" id="rua" name="rua" required class="form-control"><br>

                        <label for="numero">Número:</label>
                        <input type="text" id="numero" name="numero" required class="form-control"><br>

                        <label for="bairro">Bairro:</label>
                        <input type="text" id="bairro" name="bairro" required class="form-control"><br>

                        <label for="cidade">Cidade:</label>
                        <input type="text" id="cidade" name="cidade" required class="form-control"><br>

                        <label for="uf">UF:</label>
                        <input type="text" id="uf" name="uf" required class="form-control"><br>

                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required class="form-control"><br>

                        <button type="submit" name="addnew" class="btn btn-success">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

