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

<div class="tela-carrinho">
    <h1>Seu carrinho</h1>

    <a href="<?= BASE_URL ?>/public/pagina_inicial.php">Voltar</a>

    <hr>

    <?php if (empty($carrinho)): ?>
        <p>Seu carrinho está vazio</p>

    <?php else: ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Produto</th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Total</th>
                <th>Ações</th>
            </tr>
            <?php foreach($carrinho as $item): ?>
            <tr>
                <td>
                    <img src="<?= htmlspecialchars($item['imagem_url']) ?>" alt="Produto" width="60" style="object-fit: cover; border-radius: 5px">
                </td>
                <td><?= htmlspecialchars($item['nome']) ?></td>
                <td><?= htmlspecialchars($item['quantidade']) ?></td>
                <td><?= number_format($item['preco'], 2, ',', '.') ?></td>
                <td>R$ <?= number_format($item['quantidade'] * $item['preco'], 2, ',', '.') ?></td>
                <td class="cart-actions">
                    <a href="<?= BASE_URL ?>/app/adicionar_carrinho.php?id=<?= $item['id'] ?>" class="btn-cart btn-cart-small">+</a>
                    <a href="<?= BASE_URL ?>/app/remover_carrinho.php?id=<?= $item['id'] ?>" class="btn-cart btn-cart-small">-</a>
                    <a href="<?= BASE_URL ?>/app/deletar_carrinho.php?id=<?= $item['id'] ?>" class="btn-cart btn-cart-remove">Remover</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <hr>

        <div class="cart-summary-row">
            <!-- FRETE (ESQUERDA) -->
            <div class="cart-frete">

                <div class="frete-inputs">
                    <input type="text" id="cep" placeholder="00000-000" maxlength="9">
                    <button type="button" onclick="buscarCEP()" class="btn-cart btn-cart-small">Buscar</button>
                </div>

                <p>Frete: R$ <span id="valorFrete">0,00</span></p>
                <p id="resultado"></p>

                <div class="statecity">
                <input type="text" id="cidade" readonly>
                <input type="text" id="estado" readonly>
                </div>
            </div>

            <!-- VALOR DOS PRODUTOS (MEIO) -->
            <div class="cart-subtotal">
                <p>Valor dos produtos: R$
                    <span id="subtotalValor">
                    <?php 
                        $sum = 0;
                        foreach($carrinho as $item) {
                            $sum += $item['quantidade'] * $item['preco'];
                        } 
                        echo number_format($sum,2 ,',', '.');
                    ?>
                    </span>
                </p>
            </div>

            <!-- TOTAL FINAL (DIREITA) -->
            <div class="cart-total">
                <p>Total Final: R$ <span id="totalFinal"><?= number_format($sum, 2, ',', '.') ?></span></p>
            </div>
        </div>

        <div class="cart-finish">
            <a href="#" class="btn-cart-finish">Finalizar Compra</a>
        </div>
    <?php endif; ?>

    <script src="<?= BASE_URL ?>/scripts/utils.js"></script>
</div>
</body>
</html>