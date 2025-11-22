<?php
require_once __DIR__ . '/../app/config.php';

$tokenURL = $_GET['token'] ?? '';

$erro = $_SESSION['flash_erro'] ?? '';
$sucesso = $_SESSION['flash_sucesso'] ??'';
unset($_SESSION['flash_erro'], $_SESSION['flash_sucesso']);


if(empty($tokenURL)) {
    redirecionar('/public/index.php');
}

$token_hash = hash('sha256', $tokenURL);

$pdo = conectar_banco();
$sql = 'SELECT * FROM password_reset WHERE token_hash = ? AND expira_em > NOW()';
$stmt = $pdo->prepare($sql);
$stmt->execute([$token_hash]);

$reset_request = $stmt->fetch();

if(!$reset_request) {
    $_SESSION['flash_erro'] = 'Link de reformulação inválido ou expirado.';
    redirecionar('/public/index.php');
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar Senha</title>
</head>
<body>

<?php if($erro): ?>
        <div style="color:red;"><?= $erro ?></div>
    <?php endif; ?>
    <?php if($sucesso): ?>
        <div style="color:green;"><?= $sucesso ?></div>
    <?php endif; ?>

    
    <form action="../app/salvar_nova_senha.php" method="POST">

    <input type="hidden" name="token" value="<?=htmlspecialchars($tokenURL)?>">

        <label for="senha">Insira sua nova senha: </label> <br>
        <input type="password" name="senha" required> <br><br>

        <label for="confirmarSenha">Confirme sua senha: </label> <br>
        <input type="password" name="confirmarSenha" required> <br><br>

        <button type="submit" name="enviar">Enviar</button>
    </form>
</body>
</html>