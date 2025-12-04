<?php
require_once __DIR__ . '/../core/config.php';

$id = $_GET['id'] ?? null;

if($id && isset($_SESSION['carrinho'][$id])) {
    unset($_SESSION['carrinho'][$id]);
}

redirecionar('/public/carrinho.php');
?>