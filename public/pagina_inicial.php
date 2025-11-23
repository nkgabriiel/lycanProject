<?php

require_once __DIR__ . '/../app/verifica_sessao.php';
require_once __DIR__ . '/../app/config.php';

$usuario_exib = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuario', ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header class="header">
        <section>
        <a href="pagina_inicial.php" class="logo">
            <img src="../assets/img/iconlycan.png" alt="logo">
        </a>

    <nav class="navbar">
        <a href="pagina_inicial.php">HOME</a>
        <a href="#male">MASCULINO</a>
        <a href="#female">FEMININO</a>
        <a href="#about">CONTATO</a>
    </nav>

    <div class="icons">
        <a  href="#">
        <img width="35" height="35" class="search" src="https://img.icons8.com/ios-filled/50/search--v1.png" alt="search--v1"/>
    </a>
    <a  href="#">
        <img width="35" height="35" class="cart" src="https://img.icons8.com/ios-glyphs/30/shopping-cart--v1.png" alt="shopping-cart--v1"/>
    </a>
    <a  href="#"> 
        <img width="35" height="35" src="https://img.icons8.com/ios-glyphs/30/user-male-circle.png" alt="user-male-circle"/>
    </a>   
    </div>
     </section>
    </header>
        <div class="home-container">
        <section id="home">
            <div class="content">
                <h3>LANÇAMENTOS E PROMOÇÕES</h3>
                <a href="#" class="btn-homepage">Garanta já</a>
            </div>
        </section>
    </div>
</body>
</html>