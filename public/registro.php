<?php

require_once __DIR__ . '/../app/core/config.php';

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
            registrarLog('Cadastro', 'Novo usuário: ' . $email);
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
<body class="singUp-page">

    <!-- ================= HEADER ================= -->
    <header class="header">
        <section>
            <!-- ================= LOGO ================= -->
            <a href="pagina_inicial.php" class="logo">
                <img src="../assets/img/iconlycan.png" alt="logo">
            </a>

            <!-- ================= NAVBAR ================= -->
             <nav class="navbar">
                <a href="pagina_inicial.php">HOME</a>
                <a href="<?= BASE_URL ?>/public/pagina_busca.php?busca=camisetas">CAMISETAS</a>
                <a href="<?= BASE_URL ?>/public/pagina_busca.php?busca=calcas">CALÇAS</a>
                <a href="<?= BASE_URL ?>/public/pagina_busca.php?busca=acessorios">ACESSÓRIOS</a>
            </nav>

            <!-- ================= ICONS / PROFILE ================= -->
            <div class="icons">
                <a href="#">
                    <img width="35" height="35" class="search" src="https://img.icons8.com/ios-filled/50/search--v1.png" alt="search--v1"/>
                </a>

                <a href="#">
                    <img width="35" height="35" class="cart" src="https://img.icons8.com/ios-glyphs/30/shopping-cart--v1.png" alt="shopping-cart--v1"/>
                </a>

                <div class="profile-dropdown-wrapper" id="profileWrap">
                    <img width="35" height="35" alt="Perfil" class="profile-icon" id="profileIcon" src="https://img.icons8.com/ios-glyphs/30/user-male-circle.png" alt="user-male-circle"/>

                    <div class="profile-dropdown" id="profileMenu" role="menu" aria-labelledby="profiletoggle">
                        <?php if (!isset($_SESSION['usuario_id'])): ?>
                            <a href="index.php" class="profile-item" role="menuitem">Entrar</a>
                            <a href="registro.php" class="profile-item" role="menuitem">Cadastrar</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/public/meu_perfil.php" class="profile-item">Meu Perfil</a>
                            <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'admin'): ?>
                            <a href="<?= BASE_URL ?>/public/dashboard.php" class="profile-item">Dashboard</a>
                            <?php endif; ?>
                            <a href="../app/auth/logout.php" class="profile-item">Sair</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </header>

   <div class="tela-cadastro">
    <h2>Cadastro</h2>
    <?php if($erro) echo "<p style='color:red'>$erro</p>"; ?>
    <?php if($sucesso) echo "<p style='color:green'>".sanitizar($sucesso)."</p>"; ?>

    <form method="POST" action="registro.php">

        <div class="campo">
            <label for="nome">Nome:</label>
            <input id="nome" type="text" name="nome">
        </div>

        <div class="campo">
            <label for="email">E-mail:</label>
            <input id="email" type="email" name="email">
        </div>

        <div class="campo">
            <label for="senha">Senha:</label>
            <input id="senha" type="password" name="senha">
        </div>

        <div class="campo">
            <label for="confirmar_senha">Confirmar Senha:</label>
            <input id="confirmar_senha" type="password" name="confirmar_senha">
        </div>

        <div class="btn-singUp">
            <button type="submit" class="btn-criarconta">Criar Conta</button>
        </div>

        <p class="login-text">
            Já tem conta? <a href="index.php" class="btn-login">Login</a>
        </p>
    </form>
</div>
<script src="<?= BASE_URL ?>/scripts/utils.js" defer></script>
</body>
</html>