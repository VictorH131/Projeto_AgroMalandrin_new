<?php
    session_start();

    // Verifica se o estado na sessão coincide com o estado recebido do Google
    if (isset($_GET['state']) && $_GET['state'] !== $_SESSION['oauth2state']) {
        exit('Invalid state');
    }

    // Verifica se há um código de autorização
    if (isset($_GET['code'])) {
        $code = $_GET['code'];

        // Troca o código pelo token de acesso
        $token_url = 'https://oauth2.googleapis.com/token';
        $data = [
            'code' => $code,
            'client_id' => '871192384443-3u8b4rpu265kc57k8bubl2ngev8uldgc.apps.googleusercontent.com',
            'client_secret' => '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',  // Substitua com seu client_secret
            'redirect_uri' => 'http://localhost/salaoNovoEstilo/secure/verifica_login/google-callback.php',
            'grant_type' => 'authorization_code'
        ];

        // Envia a requisição POST para pegar o access_token
        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($token_url, false, $context);
        
        // Verifica se a resposta foi recebida corretamente
        if ($response === false) {
            exit('Falha ao obter o token de acesso: Erro na requisição');
        }

        $token_info = json_decode($response, true);

        // Agora, com o access_token, podemos buscar os dados do usuário
        if (isset($token_info['access_token'])) {
            $access_token = $token_info['access_token'];

            // Faz a requisição para obter as informações do usuário
            $userinfo_url = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $access_token;
            $userinfo = json_decode(file_get_contents($userinfo_url), true);

            // Verifica se a requisição para obter informações do usuário foi bem-sucedida
            if (isset($userinfo['email'])) {
                // Armazena os dados do usuário na sessão
                $_SESSION['userinfo'] = $userinfo;

                // Conecte-se ao banco de dados
                $host = '31.170.167.153';
                $db = 'u335479363_alunos4';
                $user = 'u335479363_alunos4';
                $pass = '$en4C2024';

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Verifica se o usuário já está cadastrado
                    $email = $userinfo['email'];
                    $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE email_usu = :email");
                    $stmt->execute(['email' => $email]);
                    $existing_user = $stmt->fetch();

                    if (!$existing_user) {
                        // Se o usuário não existir, insira no banco de dados
                        $stmt = $pdo->prepare("INSERT INTO Usuario (data_cadastro_usu, nome_usu, email_usu, status_usu) VALUES (NOW(), :nome, :email, 'ativo')");
                        $stmt->execute([
                            'nome' => $userinfo['name'],
                            'email' => $email,
                        ]);
                    }

                    // Marque o usuário como logado
                    $_SESSION['logado'] = true; // Marcar como logado
                    $_SESSION['nome'] = $userinfo['name']; // nome do usuário na sessão
                    $_SESSION['id'] = $pdo->lastInsertId(); // ID do usuário na sessão (se foi recém criado)

                    // Redireciona após a autenticação bem-sucedida
                    header('Location: /salaoNovoEstilo/index.php');
                    exit();
                } catch (PDOException $e) {
                    die("Erro ao cadastrar o usuário: " . $e->getMessage());
                }
            } else {
                exit('Falha ao obter informações do usuário');
            }
        } else {
            exit('Falha ao obter o token de acesso: ' . json_encode($token_info));
        }
    } else {
        exit('Código de autorização não recebido');
    }
?>