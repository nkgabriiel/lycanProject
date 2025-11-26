<?php
require_once __DIR__ . '/../app/config.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Recuperar senha</title>
</head>
<body class="changepassword-page">

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
    
    <div class="tela-esquecisenha">
    <h2>RECUPERAR SENHA</h2>
    <form action="../app/enviar_reset.php" method="POST">
        <label for="email">Email: </label>
        <input type="email" name="email" required>
         <div class="btn-changepassword">
        <button type="submit">Enviar</button>
        </div>
    </form>
    </div>
</body>
</html>