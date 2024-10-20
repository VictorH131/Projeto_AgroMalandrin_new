<?php
    require_once '../includes/dbconnect.php';
    
    // Ativar exibição de erros para depuração
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $success = '';
    $aviso = '';

    if (isset($_POST['update'])) { 
        // Verifica se todos os campos obrigatórios foram preenchidos
        if (empty($_POST["nome_usu"]) || empty($_POST["documento_usu"]) || empty($_POST["tipo_documento"]) || empty($_POST["data_nascimento"]) || empty($_POST["email_usu"]) || empty($_POST["rua"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["cep"]) || empty($_POST["telefone_usu"]) || empty($_POST["uf"]) || empty($_POST["senha"])) {
            $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Captura os dados do formulário
            $nome_usu = $_POST["nome_usu"];
            $nome_social = $_POST["nome_social"] ?? null;
            $documento_usu = $_POST["documento_usu"];
            $tipo_documento = $_POST["tipo_documento"];
            $data_nascimento = $_POST["data_nascimento"];
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
            $senha = password_hash($_POST["senha"], PASSWORD_BCRYPT); // Criptografando a senha

            // Validação de CPF, se necessário
            if ($tipo_documento === 'cpf' && !validarCPF($documento_usu)) {
                $aviso = "CPF inválido.";
            } else {
                $sql = "UPDATE Usuario SET 
                            nome_usu = ?, 
                            nome_social = ?, 
                            email_usu = ?, 
                            tipo_do_documento_usu = ?, 
                            documento_usu = ?, 
                            data_nascimento = ?, 
                            telefone_usu = ?, 
                            celular_usu = ?, 
                            rua = ?, 
                            numero = ?, 
                            bairro = ?, 
                            complemento = ?, 
                            cidade = ?, 
                            uf = ?, 
                            cep = ?, 
                            senha = ?
                        WHERE id_usu = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssssssssssi", 
                    $nome_usu, $nome_social, $email_usu, $tipo_documento, $documento_usu, $data_nascimento, 
                    $telefone_usu, $celular_usu, $rua, $numero, $bairro, $complemento, $cidade, $uf, $cep, $senha, $_POST['userid']);

                if ($stmt->execute()) {
                    $success = "Usuário atualizado com sucesso.";
                } else {
                    $aviso = "Erro ao atualizar usuário: " . $stmt->error;
                }
            }
        }
    }

    // Verificar o ID do usuário e obter os dados do banco de dados
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $sql = "SELECT * FROM Usuario WHERE id_usu = {$id}";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Usuário não encontrado.</div>";
        exit; // Interromper o código caso não encontre o usuário
    }



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
    <title>Editar - Usuários | Agro Malandrin</title>
     
    <!-- colocando o icone da pagina-->
    <link rel="shortcut icon" href="../../multimidia/icones/planta.png" type="image/x-icon">


    <!-- linkando ao Bootstrap v5.1 -->
        <!--CSS-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>


<div class="container">
    <?php if (!empty($aviso)): ?>
        <div class='alert alert-danger'><?= htmlspecialchars($aviso) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class='alert alert-success'><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    
        <div class="container">
            <div class="row">
                <div class="col-md-9 mx-auto">
                    <div class="box">
                        <a href="../usuarios.php" class="btn btn-secondary">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    
    <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="box">
                    
                <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Modificar Usuário</h3>

                <form action="" method="POST">
                    <input type="hidden" value="<?php echo $row['id_usu'];?>" name="userid">

                    <label for="nome_usu">Nome:</label>
                    <input type="text" id="nome_usu" name="nome_usu" value="<?php echo $row['nome_usu'];?>" required class="form-control"><br>

                    <label for="nome_social">Nome Social:</label>
                    <input type="text" id="nome_social" name="nome_social" value="<?php echo $row['nome_social'];?>" class="form-control"><br>

                    <label for="email_usu">E-mail:</label>
                    <input type="email" id="email_usu" name="email_usu" value="<?php echo $row['email_usu'];?>" required class="form-control"><br>

                    <label for="telefone_usu">Telefone:</label>
                    <input type="text" id="telefone_usu" name="telefone_usu" value="<?php echo $row['telefone_usu'];?>" class="form-control"><br>

                    <label for="celular_usu">Celular:</label>
                    <input type="text" id="celular_usu" name="celular_usu" value="<?php echo $row['celular_usu'];?>" class="form-control"><br>

                    <label for="data_nascimento">Data de Nascimento:</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo $row['data_nascimento'];?>" required class="form-control"><br>

                    <label for="tipo_documento">Tipo de Documento:</label>
                    <select name="tipo_documento" id="tipo_documento" class="form-control" required> 
                        <option value="cpf" <?= ($row['tipo_do_documento_usu'] == 'cpf') ? 'selected' : '' ?>>CPF</option>
                        <option value="rg" <?= ($row['tipo_do_documento_usu'] == 'rg') ? 'selected' : '' ?>>RG</option>
                    </select><br>

                    <label for="documento_usu">Documento:</label>
                    <input type="text" id="documento_usu" name="documento_usu" value="<?php echo $row['documento_usu'];?>" required class="form-control"><br>

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
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select><br>

                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" value="<?php echo $row['cep'];?>" required class="form-control"><br>

                    <label for="complemento">Complemento:</label>
                    <input type="text" id="complemento" name="complemento" value="<?php echo $row['complemento'];?>" class="form-control"><br>

                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required class="form-control"><br>

                    <input type="submit" name="update" class="btn btn-success" value="Atualizar Usuário">
                </form>
                <br>
            </div>
        </div>
    </div>
</div>
