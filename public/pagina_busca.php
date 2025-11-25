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
</head>
<body>
    
    <h1>Resultados da busca por <?= htmlspecialchars($termo) ?></h1>

    <a href="pagina_inicial.php">Voltar</a>

    <hr>

    <?php if(empty($produtos)): ?>
        <p>Nenhum produto encontrado.</p>
    <?php else: ?>
        <p>Total encontrado: <?= count($produtos) ?></p>
        <ul>
            <?php foreach ($produtos as $p): ?>
                <li>
                    <strong><?= htmlspecialchars($p['nome'])?></strong><br>
                    Categoria: <?= htmlspecialchars($p['categoria'])?> <br>
                    Preço: R$ <?= number_format($p['preco'], 2, ',', '.') ?> <br>

                    <?php if (!empty($p['imagem_url'])): ?>
                        <img src="<?= htmlspecialchars($p['imagem_url']) ?>" width="150" alt="Imagem do produto">
                    <?php endif; ?>

                    <p><?= nl2br(htmlspecialchars($p['descricao'])) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
