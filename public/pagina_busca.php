<?php
require_once __DIR__ . '/../app/core/config.php';

$pdo = conectar_banco();

$termo = normalize($_GET['busca'] ?? '');


if($termo === '') {
    $produto = [];
} else {
  $url = BASE_URL . '/app/api/produtos.php?busca=' . urlencode($termo);

  $json = file_get_contents($url);
  $resposta = json_decode($json, true);

  if(!is_array($resposta) ||  !isset($resposta['status'])) {
    die("<p>Erro: JSON inválido retornado pela API.</p>");
    
  }

  if($resposta['status'] !== 'ok') {
    echo '<p>Erro ao carregar resultado da busca.</p>';
    exit;
  }

  $produto = $resposta['data'];

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca por <?= htmlspecialchars($termo) ?></title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="search-page">
    
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

                <div class="search-wrapper">
                    <form method="GET" action="pagina_busca.php">
                        <input type="text" id="campo_busca" name="busca" class="search-input" placeholder="Pesquisar...">
                    </form>
                </div>

               <button type="button" id="search-toggle" class="search-btn">
                    <img width="35" height="35" class="search" src="https://img.icons8.com/ios-filled/50/search--v1.png" alt="search--v1"/>
                </button>

                <a href="carrinho.php">
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
    
    <h1>Resultados da busca por <?= htmlspecialchars($termo) ?></h1>

    <hr>

   <?php if(empty($produto)): ?>
    <p>Nenhum produto encontrado.</p>
<?php else: ?>
    <p style="text-align:center;">Total encontrado: <?= count($produto) ?></p>

    <div class="search-results">
        <?php foreach ($produto as $p): ?>
            <article class="product-card">
                <div class="product-image">
                    <?php if (!empty($p['imagem_url'])): ?>
                        <a href="<?= BASE_URL ?>/public/produto.php?id=<?= $p['id'] ?>">
                          <img src="<?= htmlspecialchars($p['imagem_url']) ?>" alt="Imagem do produto">
                        </a>
                    <?php endif; ?>
                </div>

                <div class="product-info">
                    <strong><?= htmlspecialchars($p['nome'])?></strong><br>
                    Categoria: <?= htmlspecialchars($p['categoria'])?> <br>
                    Preço: R$ <?= number_format($p['preco'], 2, ',', '.') ?> <br>
                    <p><?= nl2br(htmlspecialchars($p['descricao'])) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<script src="<?= BASE_URL ?>/scripts/utils.js" defer></script>
</body>
</html>
