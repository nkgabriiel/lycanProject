<?php 
require_once __DIR__ . '/../app/config.php';

$carrinho = $_SESSION['carrinho'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
</head>
<body>

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
            <td>
                <img src="<?= htmlspecialchars($item['imagem_url']) ?>" alt="Produto" width="60" style="object-fit: cover; border-radius: 5px">
            </td>
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