<?php
require_once __DIR__ . '/../app/config.php';

$pdo = conectar_banco();

$termo = normalize($_GET['busca'] ?? '');
$raiz = baseTerm($termo);

//Resolver busca por termos apenas semelhantes, e não quaisquer caracteres presentes na palavra
if($termo === '') {
    $produtos = [];
} else {
    $sql = 'SELECT p.*, c.nome AS categoria FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE LOWER(p.nome) LIKE :t1 OR LOWER(p.descricao) LIKE :t2 ORDER BY p.nome ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':t1', "%$raiz%", PDO::PARAM_STR);
    $stmt->bindValue(':t2', "%$raiz%", PDO::PARAM_STR);
    $stmt->execute();
    $produtos = $stmt ->fetchAll(PDO::FETCH_ASSOC);

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

   <?php if(empty($produtos)): ?>
    <p>Nenhum produto encontrado.</p>
<?php else: ?>
    <p style="text-align:center;">Total encontrado: <?= count($produtos) ?></p>

    <div class="search-results">
        <?php foreach ($produtos as $p): ?>
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
