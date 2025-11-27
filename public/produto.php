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
<body>
    <h1><?= htmlspecialchars($produto['nome']) ?></h1>

    <div class="#">

        <div class="#">
    <div class="#">
        <img src="<?= htmlspecialchars($produto['imagem_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" width="350">
    </div>

    <div class="#">
        <h2>R$ <?= number_format($produto['preco'], 2, ',', '.')?></h2>

        <p>Categoria: <?= htmlspecialchars($produto['categoria_nome']) ?></p>

        <p><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>
    </div>

    <label for="tamanho">Tamanho:</label>
    <select id="tamanho" name="tamanho">
        <option value="">Selecione</option>
        <option>P</option>
        <option>M</option>
        <option>G</option>
        <option>GG</option>
    </select>

    <br><br>

    <a href="<?= BASE_URL ?>/app/adicionar_carrinho.php?id=<?= $produto['id'] ?>" class="btn-homepage" style="font-size: 20px; padding=10px 20px;">
        Adicionar ao carrinho
    </a>
    </div>
</div>
<br><br>
<a href="<?= BASE_URL ?>/public/pagina_inicial.php">Voltar</a>
</body>
</html>