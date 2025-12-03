<?php
require_once __DIR__ . '/../app/core/config.php';

$erro = $_SESSION['flash_erro'] ?? '';
$sucesso = $_SESSION['flash_sucesso'] ?? '';
unset($_SESSION['flash_erro'], $_SESSION['flash_sucesso']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- ================= HEAD ================= -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body class="page-login">
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

    <!-- ================= MAIN CONTENT / LOGIN ================= -->
    <div class="tela-login">
        <h1>Login</h1>

        <?php if($erro) : ?>
            <div class="login-error"><?= htmlspecialchars($erro)?></div>
        <?php endif; ?>

        <?php if($sucesso) : ?>
            <div class="correct-login"><?= htmlspecialchars($sucesso)?></div>
        <?php endif; ?>

        <form action="../app/auth/autentica.php" method="post" autocomplete="off">
            <label for="email">E-mail: </label><br>
            <input type="email" name="email" id="email" required><br><br>

            <label for="senha">Senha: </label><br>
            <input type="password" name="senha" id="senha" required><br><br>

            <button type="submit" class="btn-entrar">Entrar</button>

            <a href="esqueci_senha.php">
                <p>Esqueceu sua senha?</p>
            </a>

            <a href="<?=BASE_URL?>/public/registro.php" class="btn-registro">Registre-se</a>
        </form>
    </div>

</body>
</html>