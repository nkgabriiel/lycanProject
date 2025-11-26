<?php 
$perfil_exigido = 'admin';

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/verifica_sessao.php';

$pdo = conectar_banco();

$sql = "SELECT p.*, c.nome AS categoria_nome
        FROM produtos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        ORDER BY p.nome ASC";


$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Gerir Produtos</title>
</head>
<body class="tableproduct-page">

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
    
    <div class="tela-tabelaproduto">
    <h2>Gerir Produtos</h2>
    <div class="btn-tablepage">
        <a href="<?= BASE_URL ?>/public/dashboard.php" class="btn-backtodash"> Voltar ao dashboard</a>
        <br><br>
        <a href="<?= BASE_URL ?>/public/produto_form.php" class="btn-addproduct">Adicionar Novo Produto</a>
        </div>
        <table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Imagem</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($produtos as $p): ?>
        <tr>
            <td>
                <?php if (!empty($p['imagem_url'])): ?>
                    <img src="<?= htmlspecialchars($p['imagem_url']) ?>" width="80" height="80">
                <?php else: ?>
                    Sem imagem
                <?php endif; ?>
            </td>

            <td><?= htmlspecialchars($p['nome']) ?></td>
            <td><?= htmlspecialchars($p['categoria_nome'] ?? 'Sem categoria') ?></td>
            <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
            <td><?= $p['estoque'] ?></td>

            <td>
                <a href="<?= BASE_URL ?>/public/produto_form.php?id=<?= $p['id'] ?>" class="btn-editar">
                    Editar
                </a>

                <form action="<?= BASE_URL ?>/app/produto_deletar.php"
                      method="POST" style="display:inline;"
                      onsubmit="return confirm('Deseja excluir este produto?');">
                    <input type="hidden" name="produto_id" value="<?= $p['id'] ?>">
                    <button type="submit" class="btn-delete">Excluir</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

</body>
</html>