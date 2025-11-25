<?php
require_once __DIR__ . '/config.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('/public/meu_perfil.php');
}

$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$usuario_id = trim($_SESSION['usuario_id']);

if(empty($nome) || empty($email)) {
    $_SESSION['flash_erro'] = 'Nome e email são obrigatórios.';
    redirecionar('/public/meu_perfil.php');
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_erro'] = 'Email inválido.';
    redirecionar('/public/meu_perfil.php');
}


try {
$pdo = conectar_banco();
$stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? AND id != ?');
$stmt->execute([$email, $usuario_id]);

if($stmt->fetch()) {
    $_SESSION['flash_erro'] = 'Este email já está sendo utilizado por outra conta.';
    redirecionar('/public/meu_perfil.php');
}

$stmt_update = $pdo->prepare('UPDATE usuarios SET nome = ?, email = ? WHERE id = ?');
$stmt_update->execute([$nome, $email, $usuario_id]);

$_SESSION['usuario_nome'] = $nome;
$_SESSION['usuario_email'] = $email;

registrarLog('Atualizar perfil', 'Atualizou seus dados');

$_SESSION['flash_sucesso'] = 'Dados atualizados com sucesso.';
} catch (Exception $e) {
    $_SESSION['flash_erro'] = 'Erro ao atualizar perfil.';
}

redirecionar('/public/meu_perfil.php');
?>
