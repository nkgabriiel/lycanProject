<?php
require_once __DIR__ . '/config.php';

 if (!isset($_GET['id']) ) {
    die('Produto inválido.');
 }

 $id = $_GET['id'] ?? null;

 $pdo = conectar_banco();

 $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
 $stmt->execute(['$id']);
 $produto = $stmt->fetch(PDO::FETCH_ASSOC);

 if(!$produto) {
    die('Produto não encontrado.');
 }

if(!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}


 if(isset($_SESSION['carrinho'][$id])) {
    $_SESSION['carrinho'][$id]['quantidade']++;
 }else {
    $_SESSION['carrinho'][$id] = [
        'id' => $produto['id'],
        'nome' => $produto['nome'],
        'quantidade' => 1,
        'preco' => $produto['preco'],
    ];
 } 
 
 $_SESSION['flash_sucesso'] = 'Produto adicionado ao carrinho.';
 redirecionar('/public/carrinho.php');
?>