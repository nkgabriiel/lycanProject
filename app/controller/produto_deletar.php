<?php
require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/verifica_sessao.php';

$perfil_exigido = 'admin';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('/public/dashboard.php');
    exit;
}

$id = $_POST['produto_id'] ?? null;

if(!$id) {
    die('ID inválido.');
}

$url = BASE_URL . '/app/api/produtos.php?id=' . urlencode($id);

$context = stream_context_create([
    'http' => [
        'method' => 'DELETE',
        'header' => 'Content-Type: application/json'
    ]
    ]);

    $response = @file_get_contents($url, false, $context);

    if($response === false) {
        die('Erro ao comunicar com a API');
    }

    $resposta = json_decode($response, true);

    if(!isset($resposta['status']) || $resposta['status'] !== 'ok') {
        die('Erro ao deletar: ' . ($resposta['data'] ?? 'Erro desconhecido.'));
    }

redirecionar('/public/produto_listar.php?del_ok=1');



?>
