<?php
$perfil_exigido = 'admin';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/verifica_sessao.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('/public/dashboard.php');
}

$id = trim($_POST['id']) ?? '';
$nome = trim($_POST['nome']) ??'';
$descricao = trim($_POST['descricao']) ??'';
$preco = trim($_POST['preco']) ??'';
$estoque = trim($_POST['estoque']) ??'';
$imagem_url = trim($_POST['imagem_url']) ??'';
$categoria_id = trim($_POST['categoria_id']) ??'';

if(empty($nome) || empty($descricao) || empty($estoque)) {
    $_SESSION['flash_erro'] = 'Preencha todos os campos obrigatórios.';
    redirecionar('/public/dashboard.php');
}

try {

    if(!empty($id) && is_numeric($id)) {
        
        $sql = 'UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, imagem_url = ?, categoria_id = ? WHERE id = ?';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $descricao, $preco, $estoque, $imagem_url, $categoria_id, $id]);

        registrarLog("Update Produto", 'Produto ID $id Atualizado.');

        $_SESSION['flash_sucesso'] = 'Produto atualizado com sucesso';

    } else {
        $sql = 'INSERT INTO produtos(nome, descricao, preco, estoque, imagem_url, categoria_id, data_lancamento) VALUES (?, ?, ?, ?, ?, ?, NOW()';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $descricao, $preco, $estoque, $imagem_url, $categoria_id]);

        registrarLog('Criar Produto', 'Criou o produto $nome.');
        
        $_SESSION['flash_sucesso'] = 'Produto criado com sucesso.';
    }

    redirecionar('/public/dashboard.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['flash_erro'] = 'Erro ao salvar produto.' . $e->getMessage();
    redirecionar('public/produto_form.php');
}




?>
