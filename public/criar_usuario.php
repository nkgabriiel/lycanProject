<?php
$perfil_exigido = 'admin';
require_once __DIR__ . '/../app/core/config.php';
require_once __DIR__ . '/../app/core/verifica_sessao.php';
?>

<!DOCTYPE html>
<html lang = "pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Adicionar usuário</title>
</head>
<body class="create-user">

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

    <div class="tela-adicionar">
    <h2>Adicionar Novo usuário</h2>
    <form action="../app/controller/salvar_usuario.php" method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required> <br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required> <br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required> <br><br>

        <label class="edit-perfil">Perfil:</label><br>
        <select name="perfil">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select> <br><br>

        <div class="edit-btn">
        <button type="submit" class="btn-update">Salvar usuário</button>
        <a href="dashboard.php" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
    </div>
    <script src="<?= BASE_URL ?>/scripts/utils.js" defer></script>
</body>
</html>