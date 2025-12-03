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
                        <img src="<?= htmlspecialchars($p['imagem_url']) ?>" alt="Imagem do produto">
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
</body>
</html>
