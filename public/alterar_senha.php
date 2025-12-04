<?php
require_once __DIR__ . '/../app/core/config.php';
require_once __DIR__ . '/../app/core/verifica_sessao.php';


$erro = $_SESSION['flash_erro'] ?? '';
$sucesso = $_SESSION['flash_sucesso'] ?? '';
unset($_SESSION['flash_erro'], $_SESSION['flash_sucesso']);

try {
    $pdo = conectar_banco();
    $stmt = $pdo->prepare('SELECT nome, email FROM usuarios WHERE id = ?');
    $stmt->execute([$_SESSION['usuario_id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$usuario) {
        redirecionar('/app/auth/logout.php');
    }
} catch (PDOException $e) {
    die('Erro ao carregar perfil.');
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar Senha</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="passwordreset-page">

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

<?php if($erro): ?>
        <div style="color:red;"><?= $erro ?></div>
    <?php endif; ?>
    <?php if($sucesso): ?>
        <div style="color:green;"><?= $sucesso ?></div>
    <?php endif; ?>

     <form action="<?= BASE_URL ?>/app/auth/mudar_senha.php" method="POST">
        <h3>Alterar Senha</h3>

        <label for="senha_atual">Senha atual (Obrigatório):</label><br>
        <input type="password" name="senha_atual" required><br><br>

        <label for="nova_senha">Nova senha:</label>
        <input type="password" name="nova_senha" required><br><br>

        <label for="confirmar_senha">Confirmar senha:</label>
        <input type="password" name="confirmar_senha" required><br><br>

        <button type="submit">Atualizar senha</button>
    </form>

    <script src="<?= BASE_URL ?>/scripts/utils.js" defer></script>
</body>
</html>