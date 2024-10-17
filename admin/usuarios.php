<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = '';
$aviso = '';

if (isset($_POST['addnew'])) {
    // Verifica se todos os campos necessários foram criados
    // Alterado para verificar o nome correto do campo
    if (isset($_POST["nome_usu"], $_POST["email_usu"], $_POST["tipo_documento"], $_POST["documento_usu"], $_POST["data_nascimento"], $_POST["telefone_usu"], $_POST["celular_usu"], $_POST["cep"], $_POST["rua"], $_POST["numero"], $_POST["bairro"], $_POST["complemento"], $_POST["cidade"], $_POST["uf"], $_POST["senha"])) { 
        // Verifica se os campos obrigatórios estão vazios
        if (empty($_POST["nome_usu"]) || empty($_POST["email_usu"]) || empty($_POST["tipo_documento"]) || empty($_POST["documento_usu"]) || empty($_POST["data_nascimento"]) || empty($_POST["cep"]) || empty($_POST["rua"]) || empty($_POST["numero"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["uf"]) || empty($_POST["senha"])) {
            $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Captura os dados do formulário
            $id_usu = isset($_POST["id_usu"]) ? $_POST["id_usu"] : -1;
            $nome_usu = $_POST["nome_usu"]; // ALTERADO: 'user_name' para 'nome_usu'
            $nome_social = $_POST["nome_social"] ?? null;
            $documento_usu = $_POST["documento_usu"];
            $tipo_documento = $_POST["tipo_documento"]; 
            $data_nascimento = $_POST["data_nascimento"];
            $data_cadastro_usu = date('Y-m-d H:i:s'); // Setando a data e hora atual
            $email_usu = $_POST["email_usu"];
            $rua = $_POST["rua"];
            $bairro = $_POST["bairro"];
            $cidade = $_POST["cidade"];
            $numero = $_POST["numero"];
            $cep = $_POST["cep"];
            $telefone_usu = $_POST["telefone_usu"];
            $celular_usu = $_POST["celular_usu"] ?? null;
            $uf = $_POST["uf"];
            $complemento = $_POST["complemento"] ?? null;
            $status_usu = "ativo"; // Definindo status inicial como 'ativo'
            $senha = password_hash($_POST["senha"], PASSWORD_BCRYPT); // Criptografando a senha

            // Validação do CPF
            if ($tipo_documento === 'cpf' && !validarCPF($documento_usu)) {
                $aviso = "CPF inválido.";
            } else {
                // Preparar a inserção
                $stmt = $conn->prepare("INSERT INTO Usuario (data_cadastro_usu, nome_usu, nome_social, email_usu, telefone_usu, celular_usu, data_nascimento, tipo_do_documento_usu, documento_usu, uf, cidade, bairro, rua, numero, cep, complemento_usu, status_usu, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                if ($stmt === false) {
                    $aviso = "Erro na preparação da consulta: " . $conn->error; // Exibe o erro
                } else {
                    // Verifique se $complemento é nulo e passe 'NULL' se necessário
                    $stmt->bind_param("ssssssssssssssss", 
                        $data_cadastro_usu, 
                        $nome_usu, 
                        $nome_social ?? null, // Adicionando nome_social
                        $email_usu, 
                        $telefone_usu, 
                        $celular_usu, 
                        $data_nascimento, 
                        $tipo_documento, 
                        $documento_usu, 
                        $uf, 
                        $cidade, 
                        $bairro, 
                        $rua, 
                        $numero, 
                        $cep, 
                        $complemento ?? null, // Passando complemento como nulo se não existir
                        $status_usu, 
                        $senha
                    );

                    // Executar a inserção
                    if ($stmt->execute()) {
                        $success = "Usuário cadastrado com sucesso.";
                    } else {
                        $aviso = "Erro ao cadastrar usuário: " . $stmt->error;
                    }
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
                    <br><h3>Cadastro de Usuários</h3><br>

                    <!-- Exibir mensagens de sucesso ou problema -->
                    <?php if (!empty($success)): ?>
                        <div class='alert alert-success'><?= $success ?></div>
                    <?php endif; ?>
                    <?php if (!empty($aviso)): ?>
                        <div class='alert alert-warning'><?= $aviso ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <input type="hidden" id="id_usu" name="id_usu" value="-1" class="form-control">

                        <label for="nome_usu">Nome:</label>
                        <input type="text" id="nome_usu" name="nome_usu" required class="form-control"><br>

                        <label for="nome_social">Nome Social:</label>
                        <input type="text" id="nome_social" name="nome_social" class="form-control"><br>

                        <label for="email_usu">E-mail:</label>
                        <input type="email" id="email_usu" name="email_usu" required class="form-control"><br>

                        <label for="tipo_documento">Tipo de Documento:</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-control" required> 
                            <option value="invalido">Selecione</option>
                            <option value="cpf">CPF</option>
                            <option value="rg">RG</option>
                        </select><br>

                        <label for="documento_usu">Documento:</label>
                        <input type="text" id="documento_usu" name="documento_usu" required class="form-control"><br>

                        <label for="data_nascimento">Data de Nascimento:</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" required class="form-control"><br>

                        <label for="telefone_usu">Telefone:</label>
                        <input type="text" id="telefone_usu" name="telefone_usu" placeholder="(00) 1234-5678" class="form-control"><br>

                        <label for="celular_usu">Celular:</label>
                        <input type="text" id="celular_usu" name="celular_usu" placeholder="(00) 12345-6789" required class="form-control"><br>

                        <label for="cep">CEP:</label>
                        <input type="text" id="cep" name="cep" required class="form-control"><br>

                        <label for="rua">Rua:</label>
                        <input type="text" id="rua" name="rua" required class="form-control"><br>

                        <label for="numero">Número:</label>
                        <input type="text" id="numero" name="numero" required class="form-control"><br>

                        <label for="bairro">Bairro:</label>
                        <input type="text" id="bairro" name="bairro" required class="form-control"><br>

                        <label for="complemento">Complemento:</label>
                        <input type="text" id="complemento" name="complemento" class="form-control"><br>

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
