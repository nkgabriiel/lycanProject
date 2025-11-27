<?php
require_once __DIR__ . '/geo.php';
require_once __DIR__ . '/calcular_distancia.php';

$cidade = $_POST['cidade'];
$estado = $_POST['estado'];

$destino = getCoordenadas($cidade, $estado);

if(!$destino) {
    echo json_encode(['erro' => true]);
    exit;
}

$loja_lat = -21.226;
$loja_lon = -43.774;

$distancia = distancia_km($loja_lat, $loja_lon, $destino['lat'], $destino['lon']);

$preco_frete = $distancia * 0.50;

echo json_encode([
    'erro' => false,
    'distancia' => round($distancia, 2),
    'frete' => number_format($preco_frete, 2, ',', '.')
]);

?>