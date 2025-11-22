<?php
require_once __DIR__ . '/../app/config.php';

$erro = $_SESSION['flash_erro'] ?? '';
$sucesso = $_SESSION['flash_sucesso'] ?? '';
unset($_SESSION['flash_erro'], $_SESSION['flash_sucesso']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="tela-login">
    <h1>Login</h1>

    <?php if($erro) : ?>
        <div style = "color:red"><?= htmlspecialchars($erro)?></div>
    <?php endif; ?>

    <?php if($sucesso) : ?>
        <div style = "color:green"><?= htmlspecialchars($sucesso)?></div>
        <?php endif; ?>

<form action="../app/autentica.php" method="post" autocomplete="off">
    <label for="email">E-mail: </label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="senha">Senha: </label><br>
    <input type="password" name="senha" id="senha" required><br><br>

    <button type="submit", class="btn-entrar">Entrar</button>

    <a href="esqueci_senha.php">
     <p>Esqueceu sua senha?</p>
    </a>
    <p>Ainda não tem conta?</p>
<a href="registro.php">   
   <button type="button", class="btn-registro">Registre-se</button>
    </div>
</a>
</form>
</body>
</html>