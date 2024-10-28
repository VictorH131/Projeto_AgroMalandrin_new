<?php
include_once 'includes/header.php';
require_once 'includes/dbconnect.php';


// Ativar exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = '';
$aviso = '';

// Função para validar CPF
function validarCPF($cpf) {
    // Remove caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Verifica se o CPF tem 11 dígitos ou se todos os dígitos são iguais
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Calcula os dígitos verificadores para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

// Inserir/Atualizar Cliente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nome_cli"], $_POST["documento_cli"], $_POST["tipo_do_documento_cli"], $_POST["data_nascimento"], $_POST["email_cli"], $_POST["rua"], $_POST["bairro"], $_POST["cidade"], $_POST["cep"], $_POST["telefone_cli"], $_POST["uf"])) {
        // Verifica se algum campo obrigatório está vazio
        if (empty($_POST["nome_cli"]) || empty($_POST["documento_cli"]) || empty($_POST["tipo_do_documento_cli"]) || empty($_POST["data_nascimento"]) || empty($_POST["email_cli"]) || empty($_POST["rua"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["cep"]) || empty($_POST["telefone_cli"]) || empty($_POST["uf"])) {
            $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Captura dados do formulário
            $nome_cli = $_POST["nome_cli"];
            $nome_social = $_POST["nome_social"] ?? null;
            $documento_cli = $_POST["documento_cli"];
            $tipo_do_documento_cli = $_POST["tipo_do_documento_cli"];
            $data_nascimento = $_POST["data_nascimento"];
            $data_cadastro_cli = date('Y-m-d H:i:s');
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
            $status_cli = "ativo";

            // Verificar o tipo de documento
            if ($tipo_do_documento_cli == "cpf" && !validarCPF($documento_cli)) {
                $aviso = "CPF inválido.";
            } else {
                // Preparar a inserção
                $stmt = $conn->prepare("INSERT INTO Cliente (nome_cli, nome_social, email_cli, tipo_do_documento_cli, documento_cli, data_nascimento, telefone_cli, celular_cli, cep, rua, numero, bairro, complemento, cidade, uf, status_cli, data_cadastro_cli) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // Bind de todos os parâmetros
                $stmt->bind_param("sssssssssssssssss",
                    $nome_cli,
                    $nome_social,
                    $email_cli,
                    $tipo_do_documento_cli,
                    $documento_cli,
                    $data_nascimento,
                    $telefone_cli,
                    $celular_cli,
                    $cep,
                    $rua,
                    $numero,
                    $bairro,
                    $complemento,
                    $cidade,
                    $uf,
                    $status_cli,
                    $data_cadastro_cli
                );

                // Executar a inserção
                if ($stmt->execute()) {
                    $success = "Cliente cadastrado com sucesso.";
                } else {
                    $aviso = "Erro ao cadastrar o cliente: " . $stmt->error;
                }
            }
        }
    } else {
        $aviso = "Todos os campos obrigatórios devem ser preenchidos.";
    }
}

// Desabilitar Cliente
if (isset($_GET["id_cli"]) && is_numeric($_GET["id_cli"]) && isset($_GET["del"])) {
    $id_cli = (int) $_GET["id_cli"];
    $stmt = $conn->prepare("UPDATE Cliente SET status_cli = 'desabilitado' WHERE id_cli = ?");
    $stmt->bind_param('i', $id_cli);
    
    if ($stmt->execute()) {
        $success = "Cliente desabilitado com sucesso.";
    } else {
        $aviso = "Erro ao desabilitar o cliente: " . $stmt->error;
    }
}

// Verifica se a variável 'ativo' foi passada via GET
$ativo = isset($_GET['ativo']) ? $_GET['ativo'] : 'ativo'; // Default para 'ativo'

// Consulta para buscar clientes com base no status
$sql = ($ativo === 'desabilitado') ? "SELECT * FROM Cliente WHERE status_cli = 'desabilitado'" : "SELECT * FROM Cliente WHERE status_cli = 'ativo'";
$clientes = $conn->query($sql);

if (isset($_GET['reabilitar']) && is_numeric($_GET['reabilitar'])) {
    $id_cli = (int)$_GET['reabilitar'];
    $stmt = $conn->prepare("UPDATE Cliente SET status_cli = 'ativo' WHERE id_cli = ?");
    $stmt->bind_param('i', $id_cli);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'> Cliente reabilitado com sucesso.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao reabilitar cliente.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | Agro Malandrin</title>
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

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="box">
                <br><h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastro de Cliente</h3>
                <form action="clientes.php" method="POST">
                    <input type="hidden" name="id_cli" value="<?= isset($_POST['id_cli']) ? (int) $_POST['id_cli'] : -1 ?>">

                    <div class="d-flex mt-4">
                        <div class="row">
                            <div class="col">
                                <label for="nome_cli">Nome do Cliente:</label>
                                <input type="text" id="nome_cli" name="nome_cli" required class="form-control" placeholder="Nome completo" style="width: 300px;">
                            </div>

                            <div class="col">
                                <label for="nome_social" style="position: 10%;">Nome Social:</label>
                                <input type="text" id="nome_social" name="nome_social" class="form-control" placeholder="Opcional" style="width: 300px; position: 10%;"><br>
                            </div>
                        </div>
                    </div>

                                <label for="email_cli">E-mail:</label>
                                <input type="email" id="email_cli" name="email_cli" required class="form-control" placeholder="email@exemplo.com"><br>

                    <div class="d-flex mt-0">
                        <div class="row">
                            <div class="col">
                                <label for="telefone_cli">Telefone:</label>
                                <input type="text" id="telefone_cli" name="telefone_cli" required class="form-control" placeholder="(99) 9999-9999" style="width: 300px;">
                            </div>

                            <div class="col">
                                <label for="celular_cli" style="position: 10%;">Celular:</label>
                                <input type="text" id="celular_cli" name="celular_cli" class="form-control" placeholder="(99) 99999-9999" style="width: 300px; position: 10%;"><br>
                            </div>
                        </div>
                    </div>

                                <label for="data_nascimento">Data de Nascimento:</label>
                                <input type="date" id="data_nascimento" name="data_nascimento" required class="form-control"><br>

                    <div class="d-flex mt-0">
                        <div class="row">
                            <div class="col">
                                <label for="tipo_do_documento_cli">Tipo de Documento:</label>
                                <select id="tipo_do_documento_cli" name="tipo_do_documento_cli" class="form-control" required style="width: 300px;">
                                    <option value="invalido">Selecione</option>
                                    <option value="cpf">CPF</option>
                                    <option value="rg">RG</option>
                                </select>
                            </div>

                            <div class="col">
                                <label for="documento_cli" style="position: 10%;">Documento:</label>
                                <input type="text" id="documento_cli" name="documento_cli" required class="form-control" placeholder="CPF ou RG" style="width: 300px; position: 10%;"><br>
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
                                <input type="text" id="cidade" name="cidade" required class="form-control" style="width: 300px; position: 10%;"><br>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex mt-0">
                        <div class="row">
                            <div class="col">
                                <label for="bairro">Bairro:</label>
                                <input type="text" id="bairro" name="bairro" required class="form-control" style="width: 300px;">
                            </div>

                            <div class="col">
                                <label for="cep" style="position: 10%;">CEP:</label>
                                <input type="text" id="cep" name="cep" required class="form-control" style="width: 300px; position: 10%;"><br>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex mt-0">
                        <div class="row">
                            <div class="col">
                                <label for="rua">Rua:</label>
                                <input type="text" id="rua" name="rua" required class="form-control" style="width: 300px;">
                            </div>

                            <div class="col">
                                <label for="numero" style="position: 10%;">Número:</label>
                                <input type="text" id="numero" name="numero" required class="form-control" style="width: 300px; position: 10%;"><br>
                            </div>
                        </div>
                    </div>

                    <label for="complemento">Complemento:</label>
                    <input type="text" id="complemento" name="complemento" class="form-control"><br>

                    <input type="submit" value="Cadastrar" class="btn btn-success btn-block"><br><br>
                </form>
            </div>
        </div>
    </div>
    
    <hr>
   
    <br><br>

    <div class="container">
                <div class="row">
                    <div class="col-md-3 mx-auto">
                        <div class="box">
                                    
                            <a href="clientes.php?ativo=<?= $ativo === 'desabilitado' ? 'ativo' : 'desabilitado' ?>" 
                                class="btn <?= $ativo === 'desabilitado' ? 'btn btn-primary' : 'btn btn-danger' ?>">
                                Ver Clientes <?= $ativo === 'desabilitado' ? 'Ativos' : 'Desabilitados' ?>
                            </a>

                        </div>
                    </div>
                </div>
            </div>

<div class="container">
    <div class="row">
        <div class="box">
            <h3 style="margin-left: 220px;">Clientes <?= $ativo === "desabilitado" ? "Desativados" : "Ativos" ?></h3>
            <div class="table-responsive">
                <table class="table table-bordered" style="width: 65%; margin: auto;">
                    <thead>
                        <tr class="table-success">
                            <th style="width: 2%;">ID</th>
                            <th style="width: 130px;">Nome</th>
                            <th style="width: 240px;">Tipo do Documento</th>
                            <th style="width: 170px;">Documento</th>
                            <th style="width: 5%;">Estado</th>
                            <th>Status</th>
                            <th style="width: 250px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $clientes->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id_cli'] ?></td>
                            <td><?= htmlspecialchars($row['nome_cli']) ?></td>
                            <td class="col-tipo-documento"><?= htmlspecialchars($row['tipo_do_documento_cli']) ?></td>
                            <td><?= htmlspecialchars($row['documento_cli']) ?></td>
                            <td><?= htmlspecialchars($row['uf']) ?></td>
                            <td><?= htmlspecialchars($row['status_cli']) ?></td>
                            <td>
                            <?php echo "<a href='edit/edit_cli.php?id=".$row['id_cli']."' class='btn btn-primary'>Edit</a> |"; ?>
                                <?php if ($row["status_cli"] === 'ativo'): ?>
                                    <a href="clientes.php?id_cli=<?= $row['id_cli'] ?>&del=1" onclick="return confirm('Tem certeza que deseja desabilitar este cliente?');" class="btn btn-danger">Desabilitar</a>
                                <?php else: ?>
                                    <a href="?reabilitar=<?= $row['id_cli'] ?>" class="btn btn-success">Reabilitar</a>
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
