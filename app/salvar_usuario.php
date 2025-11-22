<?php
$perfil_exigido = 'admin';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/verifica_sessao.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar("../public/dashboard.php");
}

$nome = trim($_POST["nome"] ?? '');
$email = trim($_POST['email'] ??'');
$senha = trim($_POST['senha'] ??'');
$perfil = $_POST['perfil'] ?? 'user';

if (empty($nome) || empty($email) || empty($senha)) {
   $_SESSION['flash_erro'] = 'Nome, e-mail e senha são obrigatórios!';
   redirecionar("../public/criar_usuario.php");
}

if (!in_array($perfil, ['user', 'admin'])) {
    $perfil = 'user';
}

$pdo = conectar_banco();

$stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
$stmt->execute([$email]);
if($stmt->fetch()) {
    $_SESSION['flash_erro'] = 'Este email já está cadastrado!';
    redirecionar('../public/criar_usuario.php');
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha_hash, perfil) VALUES (?, ?, ?, ?)');

if ($stmt->execute([$nome, $email, $senha_hash, $perfil])) {
    $_SESSION['flash_sucesso'] = 'Usuário criado com sucesso!';
} else {
    $_SESSION['flash_erro'] = 'Falha ao criar usuário.';
}
redirecionar('../public/dashboard.php');