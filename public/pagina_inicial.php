<?php

require_once __DIR__ . '/../app/verifica_sessao.php';
require_once __DIR__ . '/../app/config.php';

$url = BASE_URL . '/app/api/produtos.php';

$lanc = file_get_contents( $url . '?tipo=lancamento');  
$lancamento = json_decode( $lanc, true );

if($lancamento['status'] !== 'ok') {
    echo '<p>Erro ao carregar lançamentos<p>';
    exit;
}

$vend = file_get_contents( $url . '?tipo=mais_vendidos');
$mais_vendidos = json_decode( $vend, true );

if($mais_vendidos['status'] !== 'ok') {
    echo '<p>Erro ao carregar produtos mais vendidos<p>';
    exit;
}

$lancamento = $lancamento['data'];
$mais_vendidos = $mais_vendidos['data'];

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

    <!-- ================= Lançamentos ================= -->
    <section class="homepage-content" id="homepage-content">
        <h2 class="title">Lançamentos</h2>

        <div class="box-content">
            <?php foreach ($lancamento as $l): ?>
                <div class="box">
                    <a href="<?= BASE_URL ?>/public/produto.php?id=<?= $l['id'] ?>">
                        <img src="<?= htmlspecialchars($l['imagem_url']) ?>" alt="item">
                    </a>

                    <div class="info-area">
                        <!-- Linha de cima: nome + avaliações à direita -->
                        <div class="title-stars">
                            <a href="<?= BASE_URL ?>/public/produto.php?id=<?= $l['id'] ?>">
                                <h3><?= htmlspecialchars($l['nome']) ?></h3>
                            </a>

                            <div class="stars">
                                <span class="label-avaliacoes">Avaliações</span>
                                <span class="stars-icons">⭐⭐⭐⭐⭐</span>
                            </div>
                        </div>

                        <!-- Linha de baixo: preço + botão -->
                        <div class="price-row">
                            <div class="price">
                                <span class="current-price">
                                    R$ <?= number_format($l['preco'], 2, ',', '.') ?>
                                </span>
                                <span class="old-price">R$ 149,99</span>
                            </div>

                            <a href="<?= BASE_URL ?>/app/adicionar_carrinho.php?id=<?= $l['id'] ?>" class="btn-buy">
                                Adicionar ao carrinho
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <hr>

    <!-- ================= Mais Vendidas ================= -->
    <section class="homepage-content" id="homepage-content">
        <h2 class="title">Mais Vendidas</h2>
        <div class="box-content">
            <?php foreach ($mais_vendidos as $v): ?>
                <div class="box">
                    <a href="<?= BASE_URL ?>/public/produto.php?id=<?= $v['id'] ?>">
                        <img src="<?= htmlspecialchars($v['imagem_url']) ?>" alt="item">
                    </a>

                    <div class="info-area">
                        <div class="title-stars">
                            <a href="<?= BASE_URL ?>/public/produto.php?id=<?= $v['id'] ?>">
                                <h3><?= htmlspecialchars($v['nome']) ?></h3>
                            </a>

                            <div class="stars">
                                <span class="label-avaliacoes">Avaliações</span>
                                <span class="stars-icons">⭐⭐⭐⭐⭐</span>
                            </div>
                        </div>

                        <div class="price-row">
                            <div class="price">
                                <span class="current-price">
                                    R$ <?= number_format($v['preco'], 2, ',', '.') ?>
                                </span>
                                <span class="old-price">R$ 149,99</span>
                            </div>

                            <a href="<?= BASE_URL ?>/app/adicionar_carrinho.php?id=<?= $v['id'] ?>" class="btn-buy">
                                Adicionar ao carrinho
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <script src="<?= BASE_URL ?>/scripts/utils.js" defer></script>
</body>
</html>