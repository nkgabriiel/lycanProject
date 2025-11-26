<?php

require_once __DIR__ . '/../app/verifica_sessao.php';
require_once __DIR__ . '/../app/config.php';

$pdo = conectar_banco();

$sql = 'SELECT p.*, c.nome AS categoria_nome FROM produtos p LEFT JOIN categorias c ON  p.categoria_id = c.id WHERE p.estoque > 0 ORDER BY p.data_lancamento DESC LIMIT 3';

$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_vendas = 'SELECT p.*, c.nome AS categoria_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.estoque > 0 ORDER BY p.vendas DESC LIMIT 3';

$stmt = $pdo->query($sql_vendas);
$mais_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);



$usuario_exib = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuario', ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- ================= HEAD ================= -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
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

            <div id="box_busca">
                <form method="GET" action="pagina_busca.php">
                    <input type="text" id="campo_busca" name="busca" placeholder="Pesquisar...">
                </form>
            </div>

                <a href="pagina_busca.php">
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
                        <a href="../app/logout.php" class="profile-item">Sair</a>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </header>

    <!-- ================= HOME / HERO ================= -->
    <div class="home-container">
        <section id="home">
            <div class="content">
                <h3>Novidades & Promoções</h3>
                <a href="#" class="btn-homepage">Garanta já</a>
            </div>
        </section>
    </div>

    <!-- ================= HOMEPAGE CONTENT ================= -->
    <section class="homepage-content" id="homepage-content">
        <h2 class="title">Lançamentos</h2>

        <div class="box-content">
            <?php foreach ($produtos as $p): ?>

                <div class="box">
                    <a href="<?= BASE_URL ?>/public/produto.php?id=<?= $p['id'] ?>">
                    <img src="<?= htmlspecialchars($p['imagem_url']) ?>" alt="item">
                    </a>
                    <div class="title-stars">
                        <a href="<?= BASE_URL ?>/public/produto.php?id=<?= $p['id'] ?>">
                        <h3><?= htmlspecialchars($p['nome']) ?></h3>
                        </a>
                        <div class="stars">
                            <h2>Avaliações</h2>
                            ⭐⭐⭐⭐⭐
                        </div>
                    </div>
                    <div class="price">
                        R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                        <span class="through">R$ 149,99</span>
                    </div>
                    <a href="<?= BASE_URL ?>/app/adicionar_carrinho.php?id=<?= $p['id'] ?>" class="bth-homepage">Adicione ao carrinho</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <hr>

    <section class="homepage-content" id="homepage-content">
        <h2 class="title">Mais Vendidas</h2>
        <div class="box-content">
            <?php foreach ($mais_vendidos as $p): ?>
                <div class="box">
                    <a href="<?= BASE_URL ?>/public/produto.php?id=<?= $p['id'] ?>">
                    <img src="<?= htmlspecialchars($p['imagem_url']) ?>" alt="item">
                    </a>    
                        <div class="title-stars">
                            <a href="<?= BASE_URL ?>/public/produto.php?id=<?= $p['id'] ?>">
                            <h3><?= htmlspecialchars($p['nome']) ?></h3>
                            </a>
                            <div class="stars">
                                <h2>Avaliações</h2>
                                ⭐⭐⭐⭐⭐
                            </div>
                        </div>
                        <div class="price">
                            R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                            <span class="through">R$ 149,99</span>
                        </div>
                        <a href="<?= BASE_URL ?>/app/adicionar_carrinho.php?id=<?= $p['id'] ?>" class="bth-homepage">Adicione ao carrinho</a>
                </div>
            <?php endforeach; ?>    
        </div>
    </section>
<script src="<?= BASE_URL ?>/scripts/utils.js"></script>

</body>
</html>