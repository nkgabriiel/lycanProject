<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/verifica_sessao.php';

$erro = $_SESSION['flash_erro'] ?? '';
$sucesso = $_SESSION['flash_sucesso'] ?? '';
unset($_SESSION['flash_erro'], $_SESSION['flash_sucesso']);

try {
    $pdo = conectar_banco();
    $stmt = $pdo->prepare('SELECT nome, email FROM usuarios WHERE id = ?');
    $stmt->execute([$_SESSION['usuario_id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$usuario) {
        redirecionar('/app/logout.php');
    }
} catch (PDOException $e) {
    die('Erro ao carregar perfil.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Meu Perfil</title>
</head>
<body class="myprofile-page">

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
                <a href="#male">MASCULINO</a>
                <a href="#female">FEMININO</a>
                <a href="#about">CONTATO</a>
            </nav>

            <!-- ================= ICONS / PROFILE ================= -->
            <div class="icons">
                <a href="#">
                    <img width="35" height="35" class="search" src="https://img.icons8.com/ios-filled/50/search--v1.png" alt="search--v1"/>
                </a>

                <a href="#">
                    <img width="35" height="35" class="cart" src="https://img.icons8.com/ios-glyphs/30/shopping-cart--v1.png" alt="shopping-cart--v1"/>
                </a>

                <div class="profile-dropdown-wrapper">
                    <img width="35" height="35" alt="Perfil" class="profile-icon" src="https://img.icons8.com/ios-glyphs/30/user-male-circle.png"/>

                    <div class="profile-dropdown" id="profiledropdown" role="menu" aria-labelledby="profiletoggle">
                        <a href="index.php" class="profile-item" role="menuitem">Entrar</a>
                        <a href="registro.php" class="profile-item" role="menuitem">Cadastrar</a>
                    </div>
                </div>
            </div>
        </section>
    </header>

    <div class="tela-meuperfil">

        <h2>Meu Perfil</h2>

                <?php if($erro): ?> <div style="color:red; margin-bottom:15px;"><?= $erro?></div><?php endif; ?>
    <?php if($sucesso): ?> <div style="color:green; margin-bottom: 15px;"><?= $sucesso ?> </div><?php endif; ?>
        
    <form action="<?= BASE_URL ?>/app/atualizar_meu_perfil.php" method="POST">
        <h3>Dados Pessoais</h3>

        <label for="nome">Nome:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required><br><br>
        
        <div class="btns-myprofile">
        <button type="submit" class="btn-save">Salvar Dados</button>
        <a href="alterar_senha.php" class="btn-alterpassword">Alterar Senha</a> 
        </div>

        <div class="btn-back-container">
        <?php if($_SESSION['perfil'] === 'admin'): ?>
            <a href='<?= BASE_URL ?>/public/dashboard.php' class="btn-back">Voltar</a>
            <?php else: ?>
                <a href='<?= BASE_URL ?>/public/pagina_inicial.php' class="btn-back">Voltar</a>
                <?php endif; ?>
                </div>

    </form>  
</div>
</body>
</html>
