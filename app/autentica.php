<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('/public/index.php');
    exit;
}

$max_falhas = 5;
if(!isset($_SESSION['falhas_login'])) {
    $_SESSION['falhas_login'] = 0;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? null;

if (empty($email) || empty($senha)) {
    $_SESSION['flash_erro'] = 'Preencha e-mail e senha.';
    redirecionar('/public/index.php');
}

try {
    $stmt = $pdo->prepare('SELECT id, email, senha_hash, perfil, nome FROM usuarios WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
} catch (Exception $e) {
    $_SESSION['flash_erro'] = 'Erro interno. Tente novamente';
    redirecionar('/public/index.php');
    exit;
}

if(!empty($_SESSION['falhas_login']) && $_SESSION['falhas_login'] >= $max_falhas) {
    $_SESSION['flash_erro'] = 'Muitas tentativas inválidas. Tente novamente mais tarde.';
    redirecionar('/public/index.php');
    exit;
}


if(!$user || !password_verify($senha, $user['senha_hash'])) {
    $_SESSION['falhas_login']++;
    $_SESSION['flash_erro'] = 'Credenciais inválidas.';
    redirecionar('/public/index.php');
    exit;
}

session_regenerate_id(true);

$_SESSION['usuario_nome'] = $user['nome'];
$_SESSION['usuario_email'] = $user['email'];
$_SESSION['usuario_id'] = $user['id'];
$_SESSION['perfil'] = $user['perfil'];
$_SESSION['falhas_login'] = 0;

$_SESSION['flash_sucesso'] = 'Login realizado com sucesso.';

if($user['perfil'] === 'admin') {
    redirecionar('/public/dashboard.php');
} else {
    redirecionar('/public/pagina_inicial.php');
}

exit;
?>