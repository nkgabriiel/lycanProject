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
    <title>Gerir Produtos</title>
</head>
<body>

    <h2>Gerir Produtos</h2>
        <a href="<?= BASE_URL ?>/public/dashboard.php"> Voltar ao dashboard</a>
        <br><br>
        <a href="<?= BASE_URL ?>/public/produto_form.php">Adicionar Novo Produto</a>
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
                <a href="<?= BASE_URL ?>/public/produto_form.php?id=<?= $p['id'] ?>">
                    Editar
                </a>

                <form action="<?= BASE_URL ?>/app/produto_deletar.php"
                      method="POST" style="display:inline;"
                      onsubmit="return confirm('Deseja excluir este produto?');">
                    <input type="hidden" name="produto_id" value="<?= $p['id'] ?>">
                    <button type="submit">Excluir</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>