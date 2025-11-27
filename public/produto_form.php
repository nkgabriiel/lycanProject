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
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="productform-page">

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

    <div class="tela-adicionarproduto">
    <h1><?= $edicao ? 'Editar Produto' : 'Adicionar Produto' ?></h1>

    <?php if (!empty($_SESSION['flash_erro'])): ?>
        <div style="color:red;"><?= htmlspecialchars($_SESSION['flash_erro'])?></div>
    <?php unset($_SESSION['flash_erro']);
        endif; ?>

        <form action="<?= BASE_URL ?>/app/salvar_produto.php" method="POST" class="produto-form">
    <?php if ($edicao): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($produto['id'])?>">
    <?php endif; ?>

    <div class="produto-form-grid">
        <div class="col-esquerda">
            <div class="campo">
                <label>Nome do Produto:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome'])?>" required>
            </div>

            <div class="campo">
                <label>Descrição</label>
                <textarea name="descricao" rows="4"><?= htmlspecialchars($produto['descricao']) ?></textarea>
            </div>

            <div class="campo">
                <label>Preço (R$):</label>
                <input type="number" step="0.01" min="0" name="preco"
                       value="<?= htmlspecialchars($produto['preco'])?>" required>
            </div>
        </div>

        <div class="col-direita">
            <div class="campo">
                <label>Estoque:</label>
                <input type="number" min="0" name="estoque"
                       value="<?= htmlspecialchars($produto['estoque']) ?>" required>
            </div>

            <div class="campo">
                <label>URL da imagem:</label>
                <input type="text" name="imagem_url"
                       value="<?= htmlspecialchars($produto['imagem_url']) ?>">
            </div>

            <div class="campo">
                <label>Categoria:</label>
                <select name="categoria_id" required>
                    <option value="">Selecione um categoria</option>
                    <?php foreach($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= $cat['id'] == $produto['categoria_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nome'])?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="btn-productform">
        <a href="<?= BASE_URL ?>/public/dashboard.php" class="btn-backtotable">Voltar</a>
        <button type="submit" class="btn-registerproduct">
            <?= $edicao ? "Atualizar Produto" : "Cadastrar Produto" ?>
        </button>
    </div>
</form>
        </div>
</body>
</html>
