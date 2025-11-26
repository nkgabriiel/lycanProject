<?php 
require_once __DIR__ . '/config.php';

$id = $_GET['id'] ?? null;

if($id && isset($_SESSION['carrinho'][$id])) {
    $_SESSION['carrinho'][$id]['quantidade']--;

    if($_SESSION['carrinho'][$id]['quantidade'] <= 0) {
        unset($_SESSION['carrinho'][$id]);
    }
}

redirecionar('/public/carrinho.php');

?>