<?php
require_once __DIR__ . '/../core/config.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('/public/meu_perfil.php');
}

$senha_atual = $_POST['senha_atual'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';
$usuario_id = $_SESSION['usuario_id'];

if(empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)) {
    $_SESSION['flash_erro'] = 'Preencha todos os campos.';
    redirecionar('/public/meu_perfil.php');
}

if($nova_senha !== $confirmar_senha) {
    $_SESSION['flash_erro'] = 'As novas senhas não coincidem.';
    redirecionar('/public/meu_perfil.php');
}

$validar_senha = validarForcaSenha($nova_senha);

    if(!$validar_senha['valida']) {
        $_SESSION['flash_erro'] = 'Senha fraca: ' . implode(', ', $validar_senha['erros']);
        redirecionar('/public/meu_perfil.php');
    }


try {
    $pdo = conectar_banco();

    $stmt = $pdo->prepare('SELECT senha_hash FROM usuarios WHERE id = ?');
    $stmt->execute([$usuario_id]);
    $user = $stmt->fetch();

    if(!$user) {
       redirecionar('/public/meu_perfil.php');
    }

    if(!password_verify($senha_atual, $user['senha_hash'])) {
        $_SESSION['flash_erro'] = 'Sua senha atual está incorreta.';
        redirecionar('/public/meu_perfil.php');
    }

    $novo_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    $stmt_update = $pdo->prepare('UPDATE usuarios SET senha_hash = ? WHERE id = ?');
    $stmt_update->execute([$novo_hash, $usuario_id]);

    registrarLog('Alterar Senha', 'Alterou a própria senha.');

    $_SESSION['flash_sucesso'] = 'Sua senha foi alterada com sucesso.';

}catch(Exception $e) {
    $_SESSION['flash_erro'] = 'Erro ao alterar senha.';
}

redirecionar('/public/meu_perfil.php');

?>
