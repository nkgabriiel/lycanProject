<?php
$perfil_exigido = 'admin';

require_once __DIR__ . '/../app/core/config.php';
require_once __DIR__ . '/../app/core/verifica_sessao.php';

$usuario_id = $_GET['id'] ?? 0;
if(empty($usuario_id)) {
    redirecionar('../public/dashboard.php');
}

$pdo = conectar_banco();
$stmt = $pdo->prepare("SELECT id, nome, email, perfil FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

if(!$usuario) {
    $_SESSION['flash_erro'] = 'Usuário não encontrado.';
    redirecionar('../public/dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../assets/style.css">
        <title>Editar Usuário</title>
    </head>
    <body class="edit-page">
        
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

        <div class="tela-editar">
        <h2>Editar Usuário: <?= htmlspecialchars($usuario['nome'])?></h2>
        <form action="../app/controller/atualizar_usuario.php" method="POST">

            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required> <br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email'])?>" required> <br><br>

            <label class="edit-perfil">Perfil</label><br>
            <select name="perfil">
                <option value="user" <?= ($usuario['perfil'] == 'user') ? 'selected' : '' ?> >User</option>
                <option value="admin" <?= ($usuario['perfil'] == 'admin') ? 'selected' : ''  ?> >Admin</option>
            </select> <br><br>

            <div class="edit-btn">
            <button type="submit" class="btn-update">Atualizar Usuário</button>
            <a href="dashboard.php" class="btn-cancelar">Cancelar</a>
            </div>

            </div>
        </form>
    </body>
</html>