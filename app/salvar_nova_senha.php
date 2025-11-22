<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('/public/index.php');
}

$novaSenha = $_POST['senha'] ?? '';
$confirmarNovaSenha = $_POST['confirmarSenha'] ?? '';
$tokenURL = $_POST['token'] ?? '';

if(empty($tokenURL)) {
    redirecionar('/public/index.php');
}

if(empty($novaSenha) || $novaSenha !== $confirmarNovaSenha) {
    $_SESSION['flash_erro'] = 'As senhas não coincidem ou há alguma informação faltando';
    redirecionar('/public/resetar_senha.php?token=' . urlencode($tokenURL));
}

$validacao_senha = validarForcaSenha($novaSenha);

if(!$validacao_senha['valida']) {
    $_SESSION['flash_erro'] = 'Sua senha não é forte o suficiente: ' . implode(', ', $validacao_senha['erros']);
    redirecionar('/public/resetar_senha.php?token=' . urlencode($tokenURL));
}

$token_hash = hash('sha256', $tokenURL);

$pdo = conectar_banco();

$sql = 'SELECT * FROM password_reset WHERE token_hash = ? AND expira_em > NOW()';
$stmt = $pdo->prepare($sql);
$stmt->execute([$token_hash]);

$reset_request = $stmt->fetch();

if(!$reset_request) {
    $_SESSION['flash_erro'] = 'Link de redefinição inválido ou expirado. Tente novamente.';
    redirecionar('/public/index.php');
}

try {
    $usuario_id = $reset_request['usuario_id'];
    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

    $pdo->beginTransaction();

    $stmt_user_update = $pdo->prepare('UPDATE usuarios SET senha_hash = ? WHERE id = ?');
    $stmt_user_update->execute([$novaSenhaHash, $usuario_id]);

    $stmt_delete_token = $pdo->prepare('DELETE FROM password_reset WHERE id = ?');
    $stmt_delete_token->execute([$reset_request['id']]);

    $pdo ->commit();

    $_SESSION['flash_sucesso'] = 'Sua senha foi alterada com sucesso! Faça login';
    redirecionar('/public/index.php');

} catch (PDOException $e) {
    if($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['flash_erro'] = 'Ocorreu um erro ao atualizar sua senha. Tente novamente.';
        redirecionar('/public/resetar_senha.php?token=' . urlencode($tokenURL));

}