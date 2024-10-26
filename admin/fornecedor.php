<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';

// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);
$success = '';
$aviso = '';

// Inserir/Atualizar Fornecedor
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nome_for"], $_POST["documento_for"], $_POST["tipo_documento_for"], $_POST["email_for"], $_POST["telefone_for"], $_POST["celular_for"], $_POST["rua"], $_POST["bairro"], $_POST["cidade"], $_POST["cep"], $_POST["uf"])) {
        // Verifica se algum campo obrigatório está vazio
        if (empty($_POST["nome_for"]) || empty($_POST["documento_for"]) || empty($_POST["tipo_documento_for"]) || empty($_POST["email_for"]) || empty($_POST["telefone_for"]) || empty($_POST["rua"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["cep"]) || empty($_POST["uf"])) {
            $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Captura dados do formulário
            $nome_for = $_POST["nome_for"];
            $documento_for = $_POST["documento_for"];
            $tipo_documento_for = $_POST["tipo_documento_for"];
            $email_for = $_POST["email_for"];
            $telefone_for = $_POST["telefone_for"];
            $celular_for = $_POST["celular_for"] ?? null; // Celular pode ser nulo
            $rua = $_POST["rua"];
            $bairro = $_POST["bairro"];
            $cidade = $_POST["cidade"];
            $cep = $_POST["cep"];
            $uf = $_POST["uf"];
            $data_cadastro_for = date('Y-m-d H:i:s'); // Setando a data e hora atual
            $status_for = "ativo"; // Definindo status inicial como 'ativo'

            // Validação do documento com base no tipo de documento
            if ($tipo_documento_for === 'cpf' && !validarCPF($documento_for)) {
                $aviso = "CPF inválido.";
            } else {
                // Preparar a inserção
                $stmt = $conn->prepare("INSERT INTO Fornecedor (data_cadastro_for, nome_for, email_for, tipo_do_documento_for, documento_for, telefone_for, celular_for, cep, rua, bairro, cidade, uf, status_for) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                // Bind de todos os parâmetros
                $stmt->bind_param("sssssssssssss",
                    $data_cadastro_for,
                    $nome_for,
                    $email_for,
                    $tipo_documento_for,
                    $documento_for,
                    $telefone_for,
                    $celular_for,
                    $cep,
                    $rua,
                    $bairro,
                    $cidade,
                    $uf,
                    $status_for
                );

                // Executar a inserção
                if ($stmt->execute()) {
                    $success = "Fornecedor cadastrado com sucesso.";
                    $_POST = array(); // Limpa os campos
                } else {
                    $aviso = "Erro ao cadastrar fornecedor: " . $stmt->error;
                }
            }
        }
    } else {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    }
}

// Desabilitar Fornecedor
if (isset($_GET["id_for"]) && is_numeric($_GET["id_for"]) && isset($_GET["del"])) {
    $id_for = (int) $_GET["id_for"];
    $stmt = $conn->prepare("UPDATE Fornecedor SET status_for = 'desabilitado' WHERE id_for = ?");
    $stmt->bind_param('i', $id_for);

    if ($stmt->execute()) {
        $success = "Fornecedor desabilitado com sucesso.";
    } else {
        $aviso = "Erro ao desabilitar fornecedor: " . $stmt->error;
    }
}

// Verifica se a variável 'ativo' foi passada via GET
$ativo = isset($_GET['ativo']) ? $_GET['ativo'] : 'ativo'; // Default para 'ativo'

// Consulta para buscar fornecedores com base no status
$sql = ($ativo === 'desabilitado') ? "SELECT * FROM Fornecedor WHERE status_for = 'desabilitado'" : "SELECT * FROM Fornecedor WHERE status_for = 'ativo'";
$fornecedores = $conn->query($sql);

if (isset($_GET['reabilitar']) && is_numeric($_GET['reabilitar'])) {
    $id_for = (int)$_GET['reabilitar'];
    $stmt = $conn->prepare("UPDATE Fornecedor SET status_for = 'ativo' WHERE id_for = ?");
    $stmt->bind_param('i', $id_for);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Fornecedor reabilitado com sucesso.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao reabilitar fornecedor.</div>";
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
    <title>Fornecedores | Agro Malandrin</title>
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

        <!-- Formulário de Cadastro de Fornecedor -->
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="box"><br>
                        <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Fornecedor</h3><br>
                        <form action="fornecedor.php" method="POST">
                            <label for="nome_for">Nome:</label>
                            <input type="text" id="nome_for" name="nome_for" required class="form-control" value="<?= htmlspecialchars($_POST['nome_for'] ?? '') ?>"><br>

                            <label for="email_for">E-mail:</label>
                            <input type="email" id="email_for" name="email_for" required class="form-control" value="<?= htmlspecialchars($_POST['email_for'] ?? '') ?>"><br>

                            <div class="d-flex mt-0">
                                <div class="row">
                                    <div class="col">
                                        <label for="telefone_for">Telefone:</label>
                                        <input type="text" id="telefone_for" name="telefone_for" placeholder="(00) 1234-5678" required class="form-control" style="width: 300px;" value="<?= htmlspecialchars($_POST['telefone_for'] ?? '') ?>">
                                    </div>

                                    <div class="col">
                                        <label for="celular_for" style="position: 10%;">Celular:</label>
                                        <input type="text" id="celular_for" name="celular_for" placeholder="(00) 12345-6789" class="form-control" style="width: 300px; position: 10%;" value="<?= htmlspecialchars($_POST['celular_for'] ?? '') ?>"><br>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex mt-0">
                                <div class="row">
                                    <div class="col">
                                        <label for="tipo_documento_for">Tipo de Documento:</label>
                                        <select name="tipo_documento_for" id="tipo_documento_for" class="form-control" required style="width: 300px;"> 
                                            <option value="">Selecione</option>
                                            <option value="cpf" <?= (isset($_POST['tipo_documento_for']) && $_POST['tipo_documento_for'] === 'cpf') ? 'selected' : '' ?>>CPF</option>
                                            <option value="cnpj" <?= (isset($_POST['tipo_documento_for']) && $_POST['tipo_documento_for'] === 'cnpj') ? 'selected' : '' ?>>CNPJ</option>
                                        </select><br> 
                                    </div>

                                    <div class="col">
                                        <label for="documento_for" style="position: 10%;">Documento:</label>
                                        <input type="text" id="documento_for" name="documento_for" required class="form-control" style="width: 300px; position: 10%;" value="<?= htmlspecialchars($_POST['documento_for'] ?? '') ?>"><br>
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
                                        <label for="complemento">Complemento:</label>
                                        <input type="text" id="complemento" name="complemento" class="form-control" value="<?= htmlspecialchars($_POST['complemento'] ?? '') ?>"><br>

                            <button type="submit" class="btn btn-success">Cadastrar</button><br><br><br><br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


        <hr><br><br>

        <div class="container">
                <div class="row">
                    <div class="col-md-3 mx-auto">
                        <div class="box">
                                    
                        <a href="fornecedor.php?ativo=<?= $ativo === 'desabilitado' ? 'ativo' : 'desabilitado' ?>" 
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
                        <!-- Tabela de Fornecedores -->
                        <h3>Lista de Fornecedores (<?= $ativo === 'desabilitado' ? 'Desabilitados' : 'Ativos' ?>)</h3>
                        <div class="table-responsive">                            
                            <table  class="table table-bordered ">
                                <thead>
                                    <tr class="table-success">
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Celular</th>
                                        <th>Tipo</th>
                                        <th>Documento</th>
                                        <th >Endereço</th>
                                        <th>Status</th>
                                        <th style="width: 200px;">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($fornecedor = $fornecedores->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fornecedor['id_for']) ?></td>
                                            <td><?= htmlspecialchars($fornecedor['nome_for']) ?></td>
                                            <td><?= htmlspecialchars($fornecedor['email_for']) ?></td>
                                            <td><?= htmlspecialchars($fornecedor['telefone_for']) ?></td>
                                            <td><?= htmlspecialchars($fornecedor['celular_for']) ?></td>
                                            <td><?= htmlspecialchars($fornecedor['tipo_do_documento_for']) ?></td>
                                            <td><?= htmlspecialchars($fornecedor['documento_for']) ?></td>
                                            <td><?= htmlspecialchars($fornecedor['rua']) ?>, <?= htmlspecialchars($fornecedor['numero'] ?? '') ?> - <?= htmlspecialchars($fornecedor['bairro']) ?>, <?= htmlspecialchars($fornecedor['cidade']) ?> - <?= htmlspecialchars($fornecedor['uf']) ?>, <?= htmlspecialchars($fornecedor['cep']) ?></td>
                                            <td><?= htmlspecialchars($fornecedor['status_for']) ?></td>
                                            <td>
                                                    <a href="edit/edit_for.php?id=<?= $fornecedor['id_for'] ?>" class='btn btn-primary' >Editar</a> |
                                                <?php if ($fornecedor['status_for'] === 'ativo'): ?>
                                                    <a href="fornecedor.php?id_for=<?= $fornecedor['id_for'] ?>&del=1" class="btn btn-danger">Desabilitar</a>
                                                <?php else: ?>
                                                    <a href="fornecedor.php?reabilitar=<?= $fornecedor['id_for'] ?>" class="btn btn-success">Reabilitar</a>
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
