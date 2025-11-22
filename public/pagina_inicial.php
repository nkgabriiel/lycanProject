<?php

require_once __DIR__ . '/../app/verifica_sessao.php';
require_once __DIR__ . '/../app/config.php';

$usuario_exib = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuario', ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="tela-inicialcliente">
    <h1>Página Inicial</h1>
    <h2>Bem-vindo(a), <?= $usuario_exib ?>!</h2>
    
    <p>Você está logado como um usuário comum.</p>
    
    <p><a href="../app/logout.php", class="btn-saircliente">Sair</a></p>
    </div>
</body>
</html>