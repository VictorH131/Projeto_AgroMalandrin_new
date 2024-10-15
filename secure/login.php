<?php
session_start(); 

// Verificar se o usuário já está logado
if (isset($_SESSION['logado'])) {
    header('Location: ../admin/index.php'); 
    exit;
}

// Inclui o arquivo de conexão com o banco de dados
include_once "../secure/includes/dbconnect.php";
if (!$con) {
    die("A conexão com o banco de dados falhou: " . mysqli_connect_error());
}

// Limitar tentativas de login para prevenir ataques de força bruta
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SESSION['login_attempts'] >= 5) {
    die("Muitas tentativas de login falhadas. Tente novamente mais tarde.");
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
        $stmt = $con->prepare("SELECT id_usu, nome_usu, senha FROM `Usuario` WHERE email_usu = ?");
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Busca os dados do usuário
                $user = $result->fetch_assoc();
                
                    // Regenerar ID da sessão para evitar fixação de sessão
                    session_regenerate_id(true);

                    // Login bem-sucedido
                    $_SESSION['logado'] = true;
                    $_SESSION['nome'] = $user['nome_usu'];
                    $_SESSION['id'] = $user['id_usu'];

                    // Redefinir o contador de tentativas de login
                    $_SESSION['login_attempts'] = 0;

                    // Redireciona após o login
                    echo '<script>window.location.href = "../admin/index.php";</script>';
                    exit;
            } else {
                $error = "E-mail ou senha incorretos.";
                $_SESSION['login_attempts']++;
            }
            $stmt->close();  
        } else {
            $error = "Erro ao preparar a consulta: " . $con->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Salão Novo Estilo</title>
    <link rel="stylesheet" href="styles/stylelogin.css"> 

    <!-- Importar a biblioteca SHA-512 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha512/0.8.0/sha512.min.js"></script>
    <!-- Importar o script para manipular o formulário -->
    <script type="text/JavaScript" src="js/forms.js"></script>
    
</head>
<body>
    <div class="login-container">
        <div class="left-panel">
            <img src="../multimidia/logologin.png" alt="">
            <h1>Salão Novo Estilo</h1>          
        </div>
        <div class="divider"></div> <!-- Barra vertical -->
        <div class="right-panel">
            <h2>Login</h2>
            
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= $error; ?></p>
            <?php endif; ?>
            
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" id="form_login" onsubmit="hashSenha(this, this.password); return false;">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>

                <a href="#" style="font-size: 13px;">Esqueceu sua senha?</a>
                <button type="submit">Entrar</button>
            </form>

            <div class="img_icons">
                Login com:
                <a href="verifica_login/google-login.php"><img src="../multimidia/icon/google.png" alt="Google" class="icon"></a> 
            </div>

        </div>
    </div>
</body>
</html>
