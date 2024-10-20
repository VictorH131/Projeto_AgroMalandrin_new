<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';
 
// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);
$success = '';
$aviso = '';
 
// Inserir/Atualizar Usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nome_usu"], $_POST["documento_usu"], $_POST["tipo_documento"], $_POST["data_nascimento"], $_POST["email_usu"], $_POST["rua"], $_POST["bairro"], $_POST["cidade"], $_POST["cep"], $_POST["telefone_usu"], $_POST["uf"], $_POST["senha"])) {
        // Verifica se algum campo obrigatório está vazio
        if (empty($_POST["nome_usu"]) || empty($_POST["documento_usu"]) || empty($_POST["tipo_documento"]) || empty($_POST["data_nascimento"]) || empty($_POST["email_usu"]) || empty($_POST["rua"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["cep"]) || empty($_POST["telefone_usu"]) || empty($_POST["uf"]) || empty($_POST["senha"])) {
            $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Captura dados do formulário
            $nome_usu = $_POST["nome_usu"];
            $nome_social = $_POST["nome_social"] ?? null; // Nome social pode ser nulo
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
            $celular_usu = $_POST["celular_usu"] ?? null; // Celular pode ser nulo
            $uf = $_POST["uf"];
            $complemento = $_POST["complemento"] ?? null; // Complemento pode ser nulo
            $status_usu = "ativo"; // Definindo status inicial como 'ativo'
            $senha = password_hash($_POST["senha"], PASSWORD_BCRYPT); // Criptografando a senha
 
            if ($tipo_documento === 'cpf' && !validarCPF($documento_usu)) {
                $aviso = "CPF inválido.";
            } else {

                // Preparar a inserção
                $stmt = $conn->prepare("INSERT INTO Usuario (nome_usu, nome_social, email_usu, tipo_do_documento_usu, documento_usu, data_nascimento, telefone_usu, celular_usu, cep, rua, numero, bairro, complemento, cidade, uf, status_usu, senha, data_cadastro_usu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // Bind de todos os parâmetros
                $stmt->bind_param("ssssssssssssssssss", // mano fiquei 2:45 horas por conta que tinha um "s" a "menos Suicidio é vida " otima frase
                    $nome_usu,
                    $nome_social,
                    $email_usu,
                    $tipo_documento,
                    $documento_usu,
                    $data_nascimento,
                    $telefone_usu,
                    $celular_usu,
                    $cep,
                    $rua,
                    $numero,
                    $bairro,
                    $complemento,
                    $cidade,
                    $uf,
                    $status_usu,
                    $senha,
                    $data_cadastro_usu
                );


    
                // Executar a inserção
                if ($stmt->execute()) {
                    $success = "Usuário cadastrado com sucesso.";
                } else {
                    $aviso = "Erro ao cadastrar usuário: " . $stmt->error;
                }
            } 
        }
    } else {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    }
}
 
// Desabilitar Usuário
if (isset($_GET["id_usu"]) && is_numeric($_GET["id_usu"]) && isset($_GET["del"])) {
    $id_usu = (int) $_GET["id_usu"];
    $stmt = $conn->prepare("UPDATE Usuario SET status_usu = 'desabilitado' WHERE id_usu = ?");
    $stmt->bind_param('i', $id_usu);
    
    if ($stmt->execute()) {
        $success = "Usuário desabilitado com sucesso.";
    } else {
        $aviso = "Erro ao desabilitar usuário: " . $stmt->error;
    }
}




// Verifica se a variável 'ativo' foi passada via GET
$ativo = isset($_GET['ativo']) ? $_GET['ativo'] : 'ativo'; // Default para 'ativo'

// Consulta para buscar usuários com base no status
$sql = ($ativo === 'desabilitado') ? "SELECT * FROM Usuario WHERE status_usu = 'desabilitado'" : "SELECT * FROM Usuario WHERE status_usu = 'ativo'";
$usuarios = $conn->query($sql);

if (isset($_GET['reabilitar']) && is_numeric($_GET['reabilitar'])) {
    $id_usu = (int)$_GET['reabilitar'];
    $stmt = $conn->prepare("UPDATE Usuario SET status_usu = 'ativo' WHERE id_usu = ?");
    $stmt->bind_param('i', $id_usu);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'> Usuário Reabilitado com sucesso.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao reabilitar usuário.</div>";
    }
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
    <title>Usuários | Agro Malandrin</title>
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
 
        
 
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="box">

                        <br><h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Usuário</h3><br>
                        
                        <!-- Inicio do Formulario de Usuários -->
                        <form action="usuarios.php" method="POST">
                            <input type="hidden" name="id_usu" value="<?= isset($_POST['id_usu']) ? (int) $_POST['id_usu'] : -1 ?>">
 
                            <label for="nome_usu">Nome:</label>
                            <input type="text" id="nome_usu" name="nome_usu" required class="form-control"><br>
 
                            <label for="nome_social">Nome Social:</label>
                            <input type="text" id="nome_social" name="nome_social" class="form-control"><br>
 
                            <label for="email_usu">E-mail:</label>
                            <input type="email" id="email_usu" name="email_usu" required class="form-control"><br>
 
                            <label for="telefone_usu">Telefone:</label>
                            <input type="text" id="telefone_usu" name="telefone_usu" placeholder="(00) 1234-5678" class="form-control"><br>
 
                            <label for="celular_usu">Celular:</label>
                            <input type="text" id="celular_usu" name="celular_usu" placeholder="(00) 12345-6789" class="form-control"><br>
 
                            <label for="data_nascimento">Data de Nascimento:</label>
                            <input type="date" id="data_nascimento" name="data_nascimento" required class="form-control"><br>
 
                            
                            <label for="tipo_documento">Tipo de Documento:</label>
                            <select name="tipo_documento" id="tipo_documento" class="form-control" required> 
                                <option value="invalido">Selecione</option>
                                <option value="cpf">CPF</option>
                                <option value="rg">RG</option>
                            </select><br>
 
                            <label for="documento_usu">Documento:</label>
                            <input type="text" id="documento_usu" name="documento_usu" required class="form-control"><br>
 
                            <label for="rua">Rua:</label>
                            <input type="text" id="rua" name="rua" required class="form-control"><br>
 
                            <label for="numero">Número:</label>
                            <input type="text" id="numero" name="numero" required class="form-control"><br>
 
                            <label for="bairro">Bairro:</label>
                            <input type="text" id="bairro" name="bairro" required class="form-control"><br>
 
                            <label for="cidade">Cidade:</label>
                            <input type="text" id="cidade" name="cidade" required class="form-control"><br>
 
                            <label for="uf">Estado:</label><br>
                            <select id="uf" name="uf"  class="form-control" required>
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
                            </select><br>
 
                            <label for="cep">CEP:</label>
                            <input type="text" id="cep" name="cep" required class="form-control"><br>
 
                            <label for="complemento">Complemento:</label>
                            <input type="text" id="complemento" name="complemento" class="form-control"><br>
 
                            <label for="senha">Senha:</label>
                            <input type="password" id="senha" name="senha" required class="form-control"><br>
 
                            <button type="submit"  class="btn btn-success" >Cadastrar</button>
                        </form><br><br>

                        <!-- Fim do Formulario de Usuários -->
                    </div>
                </div>
            </div>
            <hr>
 
            <div class="container">
                <div class="row">
                    <div class="col-md-3 mx-auto">
                        <div class="box">
                                    
                            <a href="usuarios.php?ativo=<?= $ativo === 'desabilitado' ? 'ativo' : 'desabilitado' ?>" 
                                class="btn <?= $ativo === 'desabilitado' ? 'btn btn-primary' : 'btn btn-danger' ?>">
                                Ver Fornecedores <?= $ativo === 'desabilitado' ? 'Ativos' : 'Desabilitados' ?>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
           
            <div class="container">
               
                <div class="row">
                    
                    <div class="box">
                        <h3>Usuários <?= $ativo === "desabilitado" ? "Desativados" : "Ativos" ?></h3>
                        <div class="table-responsive">

                            
                            <table  class="table table-bordered ">
                                <thead>
                                    <tr class="table-success">
                                        <th >ID</th>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Telefone</th>
                                        <th>Documento</th> <!-- Adicionado campo Documento -->
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php while ($row = $usuarios->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id_usu'] ?></td>
                                        <td><?= $row['nome_usu'] ?></td>
                                        <td><?= $row['email_usu'] ?></td>
                                        <td><?= $row['telefone_usu'] ?></td>
                                        <td><?= $row['documento_usu'] ?></td>
                                        <td><?= $row['status_usu'] ?></td>
                                        <td>
                                        <?php echo "<a href='edit/edit_usu.php?id=".$row['id_usu']."' class='btn btn-primary'>Edit </a> |"; ?>
                                            
                                            <?php if ($ativo === 'desabilitado'): ?>
                                                <a href="?reabilitar=<?= $row['id_usu'] ?>" class="btn btn-success">Reabilitar</a>
                                            <?php else: ?>
                                                <a href="usuarios.php?id_usu=<?= $row['id_usu'] ?>&del=1" onclick="return confirm('Tem certeza que deseja desabilitar este serviço?');" class="btn btn-danger">Desligar</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>

                            
                        </div>
                    </div>
                    
                </div>
            </div>
 
</body>
</html>