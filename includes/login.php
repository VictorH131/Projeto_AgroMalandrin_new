<?php
session_start(); 

// Verificar se o usuário já está logado
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    header('Location: ../admin/principal.php'); 
    exit;
}

// Inclui o arquivo de conexão com o banco de dados
include_once "internal/dbconnect.php"; // Ajuste o caminho conforme necessário
if (!$conn) {
    die("A conexão com o banco de dados falhou: " . mysqli_connect_error());
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter e limpar os valores dos campos
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';

    // Verificar se os campos estão preenchidos
    if (empty($email)) {
        $error = "E-mail é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "E-mail inválido.";
    } elseif (empty($password)) {
        $error = "Senha é obrigatória.";
    } else {
        // Preparar a consulta para obter o hash da senha
        $stmt = $conn->prepare("SELECT id_usu, nome_usu, senha FROM `Usuario` WHERE email_usu = ?");
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Busca os dados do usuário
                $user = $result->fetch_assoc();
                
                // Verifica se a senha informada corresponde ao hash da senha armazenada
                if (password_verify($password, $user['senha'])) {
                    // Regenerar ID da sessão para evitar fixação de sessão
                    session_regenerate_id(true);

                    // Login bem-sucedido
                    $_SESSION['logado'] = true;
                    $_SESSION['nome'] = $user['nome_usu']; // Armazena o nome do usuário
                    $_SESSION['id'] = $user['id_usu'];

                    // Redireciona após o login
                    header('Location: ../admin/principal.php');
                    exit;
                } else {
                    $error = "E-mail ou senha incorretos.";
                }
            } else {
                $error = "E-mail ou senha incorretos.";
            }
            $stmt->close();  
        } else {
            $error = "Erro ao preparar a consulta: " . $conn->error;
        }
    }
}

include 'internal/header.php'; // Ajuste o caminho conforme necessário
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Agro Malandrin</title>
    <link rel="stylesheet" href="estilos/estilo2.css">
    

    <!-- Importar a biblioteca SHA-512 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha512/0.8.0/sha512.min.js"></script>
</head>

<body id="pag_login">
    <div id="login">     
        <div class="tableLogin">
            <h2>Login</h2>
            
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= $error; ?></p>
            <?php endif; ?>
            
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" id="form_login" onsubmit="hashSenha(this, this.password); return false;">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" placeholder="coloque seu E-mail" required>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" placeholder="coloque sua Senha" required>

                <div id="link">
                    <a href="#" style="font-size: 13px;">Esqueceu sua senha?</a>
                </div>
                
                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
