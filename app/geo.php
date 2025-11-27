<?php
function getCoordenadas($cidade, $estado) {
    $cidade = urlencode($cidade);
    $estado = urlencode($estado);

    $url = "https://nominatim.openstreetmap.org/search?city=$cidade&state=$estado&country=Brasil&format=json&limit=1";

    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: LycanStore/1.0\r\n"
        ]
        ]);
        $json = @file_get_contents($url, false, $context);

    if (!$json) {
        return false;
    }


    $dados = json_decode($json, true);

    if(!empty($dados)) {
        return [
            'lat' => $dados[0]['lat'],
            'lon' => $dados[0]['lon']
        ];
    }
    return false;
}


?>