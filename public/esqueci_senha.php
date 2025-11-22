<?php
require_once __DIR__ . '/../app/config.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha</title>
</head>
<body>
    <h2>RECUPERAR SENHA</h2>
    <form action="../app/enviar_reset.php" method="POST">
        <label for="email">Email: </label>
        <input type="email" name="email" required>

        <button type="submit">Enviar</button>
    </form>
</body>
</html>