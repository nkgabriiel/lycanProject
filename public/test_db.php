<?php

require_once "config.php";

$db = conectar_banco();

$stmt = $db->query("SHOW TABLES;");
$dados = $stmt->fetchAll();

echo "<pre>";
print_r($dados);