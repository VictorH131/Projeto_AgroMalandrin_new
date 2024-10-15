<?php
    include_once '../secure/verifica_login/session.php';  // Verificando se você está logado
    include_once "../secure/includes/dbconnect.php";

    $erro = '';
    $success = '';

    // Inserir/Atualizar Usuário
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["nome_usu"], $_POST["documento_usu"], $_POST["tipo_documento"], $_POST["data_nascimento"], $_POST["email_usu"], $_POST["rua"], $_POST["bairro"], $_POST["cidade"], $_POST["cep"], $_POST["telefone_usu"], $_POST["uf"], $_POST["senha"])) {
            if (empty($_POST["nome_usu"]) || empty($_POST["documento_usu"]) || empty($_POST["tipo_documento"]) || empty($_POST["data_nascimento"]) || empty($_POST["email_usu"]) || empty($_POST["rua"]) || empty($_POST["bairro"]) || empty($_POST["cidade"]) || empty($_POST["cep"]) || empty($_POST["telefone_usu"]) || empty($_POST["uf"]) || empty($_POST["senha"])) {
                $erro = "Todos os campos obrigatórios devem ser preenchidos.";
            } else {
                $id_usu = isset($_POST["id_usu"]) ? $_POST["id_usu"] : -1;
                $nome_usu = $_POST["nome_usu"];
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

                if ($id_usu == -1) { // Inserir novo usuário
                    $stmt = $con->prepare("INSERT INTO Usuario (data_cadastro_usu, nome_usu, nome_social, documento_usu, tipo_do_documento_usu, data_nascimento, email_usu, rua, bairro, cidade, cep, telefone_usu, celular_usu, numero, uf, complemento, status_usu, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    $stmt->bind_param("ssssssssssssssssss", $data_cadastro_usu, $nome_usu, $nome_social, $documento_usu, $tipo_documento, $data_nascimento, $email_usu, $rua, $bairro, $cidade, $cep, $telefone_usu, $celular_usu, $numero, $uf, $complemento, $status_usu, $senha);
                
                    if ($stmt->execute()) {
                        $success = "Usuário cadastrado com sucesso.";
                    } else {
                        $erro = "Erro ao cadastrar usuário: " . $stmt->error;
                    }
                }
            }
        } else {
            $erro = "Todos os campos obrigatórios devem ser preenchidos.";
        }
    }

    // Desabilitar Usuário
    if (isset($_GET["id_usu"]) && is_numeric($_GET["id_usu"]) && isset($_GET["del"])) {
        $id_usu = (int) $_GET["id_usu"];
        $stmt = $con->prepare("UPDATE Usuario SET status_usu = 'desabilitado' WHERE id_usu = ?");
        $stmt->bind_param('i', $id_usu);
        if ($stmt->execute()) {
            $success = "Usuário desabilitado com sucesso.";
        } else {
            $erro = "Erro ao desabilitar usuário: " . $stmt->error;
        }
    }

    // Consulta para buscar todos os usuários ativos
    $sql = "SELECT * FROM Usuario WHERE status_usu = 'ativo'";
    $usuarios = $con->query($sql);
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>+Usuários | Salão Novo Estilo</title>
    <link rel="stylesheet" href="styles/usuario.css">
    <link rel="stylesheet" href="styles/forms.css">
</head>
<?php 
    include_once "includes/nav.php"; 
?>
<body>
    <div class="container">

    <h1>Cadastro de Usuários</h1>

   
    <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>


    <!-- Formulário para adicionar ou editar usuário -->
    <form action="forms_usu.php" method="POST">
        <input type="hidden" name="id_usu" value="<?= isset($_POST['id_usu']) ? (int) $_POST['id_usu'] : -1 ?>">

        <label for="nome_usu">Nome:</label>
        <input type="text" id="nome_usu" name="nome_usu" required>

        <label for="nome_social">Nome Social:</label>
        <input type="text" id="nome_social" name="nome_social">

        <label for="email_usu">E-mail:</label>
        <input type="email" id="email_usu" name="email_usu" required>

        <label for="telefone_usu">Telefone:</label>
        <input type="text" id="telefone_usu" name="telefone_usu" placeholder="(00) 1234-5678">

        <label for="celular_usu">Celular:</label>
        <input type="text" id="celular_usu" name="celular_usu" placeholder="(00) 12345-6789">

        <label for="data_nascimento">Data de Nascimento:</label>
        <input type="date" id="data_nascimento" name="data_nascimento" required>

        <label for="tipo_documento">Tipo do Documento:</label>
        <input type="text" id="tipo_documento" name="tipo_documento" required>

        <label for="documento_usu">Documento:</label>
        <input type="text" id="documento_usu" name="documento_usu" required>

        <label for="cep">CEP:</label>
        <input type="text" id="cep" name="cep" placeholder="00000-000">

        <label for="rua">Rua:</label>
        <input type="text" id="rua" name="rua" required>

        <label for="numero">Número:</label>
        <input type="text" id="numero" name="numero" required>

        <label for="bairro">Bairro:</label>
        <input type="text" id="bairro" name="bairro" required>

        <label for="cidade">Cidade:</label>
        <input type="text" id="cidade" name="cidade" required>

        <label for="uf">UF:</label>
        <input type="text" id="uf" name="uf" required>

        <label for="complemento">Complemento:</label>
        <input type="text" id="complemento" name="complemento">

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>

    <h2>Lista de Usuários Ativos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
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
                    <td><?= $row['status_usu'] ?></td>
                    <td>                     
                        <a href="forms_usu.php?id_usu=<?= $row['id_usu'] ?>" class="edit">Editar</a> |
                        <a href="forms_usu.php?id_usu=<?= $row['id_usu'] ?>&del=1" onclick="return confirm('Tem certeza que deseja desabilitar este serviço?');">Desabilitar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
