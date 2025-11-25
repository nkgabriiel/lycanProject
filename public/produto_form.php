<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/verifica_sessao.php';

if($_SESSION['perfil'] !== 'admin') {
    redirecionar('/public/pagina_inicial.php');
}

$pdo = conectar_banco();

$edicao =  isset($_GET['id']) && is_numeric($_GET['id']);

$produto = [
    'id' => '',
    'nome' => '',
    'descricao' => '',
    'preco' => '',
    'estoque' => '',
    'imagem_url' => '',
    'categoria_id' => ''
];

if($edicao) {
    $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
    $stmt->execute([$_GET['id']]);

    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$produto) {
        $_SESSION['flash_erro'] = 'Produto não encontrado.';
        redirecionar('/public/dashboard.php');
    }
}

$stmt_cat = $pdo->query('SELECT id, nome FROM categorias ORDER BY nome ASC');
$categorias = $stmt_cat->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $edicao ? 'Editar Produto' : 'Adicionar Produto' ?></title>
</head>
<body>
    <h1><?= $edicao ? 'Editar Produto' : 'Adicionar Produto' ?></h1>

    <a href="<?= BASE_URL ?>/public/dashboard.php">Voltar</a>

    <?php if (!empty($_SESSION['flash_erro'])): ?>
        <div style="color:red;"><?= htmlspecialchars($_SESSION['flash_erro'])?></div>
    <?php unset($_SESSION['flash_erro']);
        endif; ?>

        <form action="<?= BASE_URL ?>/app/salvar_produto.php" method="POST">
            <?php if ($edicao): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($produto['id'])?>" >
            <?php endif; ?>
                <label>Nome do Produto: </label>
                <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome'])?>" required>

                <label>Descrição</label>
                <textarea name="descricao" rows="4"><?= htmlspecialchars($produto['descricao']) ?></textarea>

                <label>Preço (R$): </label>
                <input type="number" step="0.01" min="0" name="preco" value="<?= htmlspecialchars($produto['preco'])?>" required>
                
                <label>Estoque: </label>
                <input type="number" min="0" name="estoque" value="<?= htmlspecialchars($produto['estoque']) ?>" required>

                <label>URL da imagem: </label>
                <input type="text" name="imagem_url" value="<?= htmlspecialchars($produto['imagem_url']) ?>">

                <label>Categoria: </label>
                <select name="categoria_id" required>
                    <option value="">Selecione um categoria</option>

                    <?php foreach($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $produto['categoria_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nome'])?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">
                    <?= $edicao ? "Atualizar Produto" : "Cadastrar Produto" ?>
                </button>
        </form>
</body>
</html>
