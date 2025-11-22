<?php
require_once __DIR__ . '/config.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
   redirecionar('/public/index.php');
}

$email = trim($_POST['email']??'');

$pdo = conectar_banco();

$stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
$stmt->execute([$email]);

$usuario = $stmt->fetch();

if($usuario) {
$token = generateToken();
$token_hash = hash('sha256', $token);

$expira_em = date('Y-m-d H:i:s',strtotime('+1 hour'));

$stmt_insert = $pdo->prepare('INSERT INTO password_reset (usuario_id, token_hash, expira_em) VALUES (?, ?, ?)');
$stmt_insert->execute([$usuario['id'], $token_hash, $expira_em]);

$link = BASE_URL . '/public/resetar_senha.php?token=' . $token;

echo 'link de reset (copie e cole no navegador): ' . $link;
exit;

}

$_SESSION['flash_sucesso'] = 'Caso exista uma conta correspondente ao email escrito, um link de recuperação de senha será enviado.';
   redirecionar('../public/index.php');
?>