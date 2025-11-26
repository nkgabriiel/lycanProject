<?php 
require_once __DIR__ . '/../app/config.php';

$carrinho = $_SESSION['carrinho'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Carrinho</title>
</head>
<body class="cart-page">

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

<h1>Seu carrinho</h1>

<a href="<?= BASE_URL ?>/public/pagina_inicial.php">Voltar</a>

<hr>

<?php if (empty($carrinho)): ?>
    <p>Seu carrinho está vazio</p>

<?php else: ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preçoo</th>
            <th>Total</th>
            <th>Ações</th>
        </tr>
        <?php foreach($carrinho as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['nome']) ?></td>
            <td><?= htmlspecialchars($item['quantidade']) ?></td>
            <td><?= number_format($item['preco'], 2, ',', '.') ?></td>
            <td>R$ <?= number_format($item['quantidade'] * $item['preco'], 2, ',', '.') ?></td>
            <td>
                <a href="<?= BASE_URL ?>/app/adicionar_carrinho.php?id=<?= $item['id'] ?>">+</a>
                <a href="<?= BASE_URL ?>/app/remover_carrinho.php?id=<?= $item['id'] ?>">-</a>
                <a href="<?= BASE_URL ?>/app/deletar_carrinho.php?id=<?= $item['id'] ?>">Remover</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <hr>
    <h2>Total Geral: R$
    <?php 
       $sum = 0;
       foreach($carrinho as $item) {
        $sum = $item['quantidade'] * $item['preco'];
       } 
       echo number_format($sum,2 ,',', '.');

    ?>
    </h2>

    <a href="#" class="btn-homepage">Finalizar Compra</a>
<?php endif; ?>
</body>
</html>