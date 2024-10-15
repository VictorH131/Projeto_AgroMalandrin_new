<?php
    session_start(); // Inicia a sessão

    $client_id = '871192384443-3u8b4rpu265kc57k8bubl2ngev8uldgc.apps.googleusercontent.com'; // Substitua com seu client_id correto
    $redirect_uri = 'http://localhost/salaoNovoEstilo/secure/verifica_login/google-callback.php'; // Certifique-se que este URI está registrado no Google Cloud Console
    $scope = 'email profile';
    $state = bin2hex(random_bytes(16)); // Gera um estado aleatório para proteção CSRF
    $_SESSION['oauth2state'] = $state; // Armazena o estado na sessão

    // Cria a URL de autorização
    $auth_url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
        'response_type' => 'code',
        'client_id' => $client_id,
        'redirect_uri' => $redirect_uri,
        'scope' => $scope,
        'state' => $state,
    ]);
    

    // Redireciona para a autenticação do Google
    header('Location: ' . $auth_url);
    exit();
?>