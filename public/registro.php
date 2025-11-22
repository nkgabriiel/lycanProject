<?php

require_once __DIR__ . '/../app/config.php';

if (isset($_SESSION['usuario_id'])) {
   redirecionar('/public/dashboard.php');
}

$erro = $_SESSION['flash_erro'] ?? '';
$sucesso = $_SESSION['flash_sucesso'] ?? '';
unset($_SESSION['flash_erro'], $_SESSION['flash_sucesso']);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST ['senha'] ?? '';
    $confirma = $_POST['confirmar_senha'] ?? '';

    $_SESSION['form_data'] = [
        'nome' => $nome,
        'email' => $email
    ];

    $erros = [];

    if(!$nome || !$email || !$senha || !$confirma) $erros[] = 'Todos os campos são obrigatórios';
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $erros[] = 'E-mail inválido.';

    $validacao_senha = validarForcaSenha($senha);
    if(!$validacao_senha['valida']) {
        $erros =array_merge($erros, $validacao_senha['erros']);
    }
    if(empty($erros)) {
        $pdo = conectar_banco();
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' =>$email]);
        if($stmt ->fetch()) $erros[] = 'E-mail já cadastrado';
    }

    if(empty($erros)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare ("INSERT INTO usuarios (email, senha_hash, nome) VALUES (:email, :senha_hash, :nome)");
        
        $params = [
        ':email' => $email,
        ':senha_hash' => $senha_hash,
        ':nome' => $nome
    ];
        if($stmt->execute($params)) {
            $_SESSION['flash_sucesso'] = 'Cadastro Realizado, faça login!';
            redirecionar('/public/index.php');
        }
    }else {
        $erros[] = 'Erro ao criar conta.';
    }
    if(!empty($erros)) $_SESSION['flash_erro'] = implode('<br>',$erros);
    redirecionar('/public/registro.php');
    
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastro</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="tela-cadastro">
<h2>Cadastro</h2>
<?php if($erro) echo "<p style='color:red'>$erro</p>"; ?>
<?php if($sucesso) echo "<p style='color:green'>".sanitizar($sucesso)."</p>"; ?>

<form method="POST" action="registro.php">
<label>Nome: <input type="text" name="nome"></label><br><br>
<label>E-mail: <input type="email" name="email"></label><br><br>
<label>Senha: <input type="password" name="senha"></label><br><br>
<label>Confirmar Senha: <input type="password" name="confirmar_senha"></label><br><br>
<button type="submit", class="btn-criarconta">Criar Conta</button>
</form>

<p>Já tem conta? <a href="index.php", class="btn-login">Login</a></p>
</div>
</body>
</html>