<?php
require_once __DIR__ . '/../app/config.php';

$pdo =  conectar_banco();

$id = $_GET['id'] ?? null;

if (!$id) {
    die('Produto inválido');
}

$url = BASE_URL . '/app/api/produtos.php?id=' . urlencode($id);

$json = file_get_contents($url);
$produto = json_decode($json, true);

if ($produto['status'] !== 'ok') {
    echo '<p>Produto não encontrado</p>';
} 

$produto = $produto['data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto['nome']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body class="product-page">

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
                    <img width="35" height="35" class="search"
                         src="https://img.icons8.com/ios-filled/50/search--v1.png" alt="search--v1"/>
                </a>

                <a href="#">
                    <img width="35" height="35" class="cart"
                         src="https://img.icons8.com/ios-glyphs/30/shopping-cart--v1.png" alt="shopping-cart--v1"/>
                </a>

                <div class="profile-dropdown-wrapper">
                    <img width="35" height="35" alt="Perfil" class="profile-icon"
                         src="https://img.icons8.com/ios-glyphs/30/user-male-circle.png"/>

                    <div class="profile-dropdown" id="profiledropdown" role="menu" aria-labelledby="profiletoggle">
                        <a href="index.php" class="profile-item" role="menuitem">Entrar</a>
                        <a href="registro.php" class="profile-item" role="menuitem">Cadastrar</a>
                    </div>
                </div>
            </div>
        </section>
    </header>

    <!-- TÍTULO DO PRODUTO -->
    <h1 class="product-title-page">
        <?= htmlspecialchars($produto['nome']) ?>
    </h1>

    <!-- WRAPPER GERAL -->
    <div class="product-wrapper">

        <!-- ============ GALERIA (thumbs + imagem grande) ============ -->
        <div class="product-gallery">

            <!-- Thumbs à esquerda -->
            <div class="product-thumbs">
                <?php for ($i = 0; $i < 4; $i++): ?>
                    <button class="product-thumb-btn <?= $i === 0 ? 'active' : '' ?>"
                            data-image="<?= htmlspecialchars($produto['imagem_url']) ?>">
                        <img src="<?= htmlspecialchars($produto['imagem_url']) ?>"
                             alt="<?= htmlspecialchars($produto['nome']) ?>">
                    </button>
                <?php endfor; ?>
            </div>

            <!-- Imagem principal -->
            <div class="product-main-image">
                <img id="main-product-image"
                     src="<?= htmlspecialchars($produto['imagem_url']) ?>"
                     alt="<?= htmlspecialchars($produto['nome']) ?>">
            </div>
        </div>

        <!-- ============ BOX DE INFORMAÇÕES ============ -->
        <aside class="product-info">
            <!-- Avaliação -->
            <div class="product-rating">
                <span class="product-stars">⭐⭐⭐⭐⭐</span>
                <span class="product-rating-text">Avaliações</span>
            </div>

            <!-- Preços -->
            <div class="product-price-box">
                <span class="product-price">
                    R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                </span>
                <span class="product-old-price">R$ 149,99</span>
            </div>

            <!-- Categoria -->
            <p class="product-category">
                Categoria: <?= htmlspecialchars($produto['categoria_nome']) ?>
            </p>

            <!-- Descrição -->
            <p class="product-description">
                <?= nl2br(htmlspecialchars($produto['descricao'])) ?>
            </p>

            <!-- Tamanho -->
            <div class="product-size">
                <label for="tamanho">Tamanho</label>
                <select id="tamanho" name="tamanho">
                    <option value="">Selecione</option>
                    <option value="P">P</option>
                    <option value="M">M</option>
                    <option value="G">G</option>
                    <option value="GG">GG</option>
                </select>
            </div>

            <!-- Botões -->
            <div class="product-actions">
                <a href="<?= BASE_URL ?>/app/adicionar_carrinho.php?id=<?= $produto['id'] ?>"
                   class="btn-product-primary">
                    Adicionar ao carrinho
                </a>

                <a href="<?= BASE_URL ?>/public/pagina_inicial.php"
                   class="btn-product-primary btn-product-secondary">
                    Voltar
                </a>
            </div>
        </aside>
    </div>

    <script>
        document.querySelectorAll('.product-thumb-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.getAttribute('data-image');
                document.getElementById('main-product-image').src = src;

                document.querySelectorAll('.product-thumb-btn')
                    .forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    </script>
</body>
</html>
