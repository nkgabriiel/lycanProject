<?php 
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

$pdo = conectar_banco();

$stmt = $pdo->query('SELECT id, nome, preco, imagem_url, vendas FROM produtos');
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'status' => 'ok',
    'data' => $produtos
]);
