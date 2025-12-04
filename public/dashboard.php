<?php
$perfil_exigido = 'admin';

require_once __DIR__ . '/../app/core/config.php';
require_once __DIR__ . '/../app/core/verifica_sessao.php';

$usuario_exib = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Admin', ENT_QUOTES, 'UTF-8');
$admin_id_logado = $_SESSION['usuario_id'];

$lista_usuarios = [];

if($_SESSION['perfil'] === 'admin') {

    try {
        $pdo=conectar_banco();

        $stmt = $pdo->query('SELECT id, nome, email, perfil FROM usuarios ORDER BY nome ASC');
        $lista_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Erro ao buscar usuários: ". $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="dashboard-page">

<!-- ================= HEADER ================= -->
    <header class="header">
        <section>
            <!-- ================= LOGO ================= -->
            <a href="pagina_inicial.php" class="logo">
                <img src="../assets/img/iconlycan.png" alt="logo">
            </a>

            <!-- ================= NAVBAR ================= -->
             <nav class="navbar">
                <a href="pagina_inicial.php">HOME</a>
                <a href="<?= BASE_URL ?>/public/pagina_busca.php?busca=camisetas">CAMISETAS</a>
                <a href="<?= BASE_URL ?>/public/pagina_busca.php?busca=calcas">CALÇAS</a>
                <a href="<?= BASE_URL ?>/public/pagina_busca.php?busca=acessorios">ACESSÓRIOS</a>
            </nav>

            <!-- ================= ICONS / PROFILE ================= -->
            <div class="icons">

                <div class="search-wrapper">
                    <form method="GET" action="pagina_busca.php">
                        <input type="text" id="campo_busca" name="busca" class="search-input" placeholder="Pesquisar...">
                    </form>
                </div>

               <button type="button" id="search-toggle" class="search-btn">
                    <img width="35" height="35" class="search" src="https://img.icons8.com/ios-filled/50/search--v1.png" alt="search--v1"/>
                </button>

                <a href="carrinho.php">
                    <img width="35" height="35" class="cart" src="https://img.icons8.com/ios-glyphs/30/shopping-cart--v1.png" alt="shopping-cart--v1"/>
                </a>

                <div class="profile-dropdown-wrapper" id="profileWrap">
                    <img width="35" height="35" alt="Perfil" class="profile-icon" id="profileIcon" src="https://img.icons8.com/ios-glyphs/30/user-male-circle.png" alt="user-male-circle"/>

                    <div class="profile-dropdown" id="profileMenu" role="menu" aria-labelledby="profiletoggle">
                        <?php if (!isset($_SESSION['usuario_id'])): ?>
                            <a href="index.php" class="profile-item" role="menuitem">Entrar</a>
                            <a href="registro.php" class="profile-item" role="menuitem">Cadastrar</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/public/meu_perfil.php" class="profile-item">Meu Perfil</a>
                            <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'admin'): ?>
                            <a href="<?= BASE_URL ?>/public/dashboard.php" class="profile-item">Dashboard</a>
                            <?php endif; ?>
                            <a href="../app/auth/logout.php" class="profile-item">Sair</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </header>

    <div class="tela-inicialadmin">
        
    <h1>Bem-vindo, <?= $usuario_exib ?></h1>

    <h2>Gerenciamento de usuários</h2>

    <div class="btn-dashbody">
        <a href="../app/auth/logout.php" class="btn-cancel">Sair</a> <hr>
        <a href="criar_usuario.php" class="btn-add">+ Adicionar Usuário</a>
    </div>

    <?php if (!empty($_SESSION['flash_sucesso'])): ?>
        <div style="color:green"><?= htmlspecialchars($_SESSION['flash_sucesso'])?></div>
        <?php unset($_SESSION['flash_sucesso']); ?>
    <?php endif; ?>
        <?php if (!empty($_SESSION['flash_erro'])): ?>
            <div style="color:red;"><?= htmlspecialchars($_SESSION['flash_erro'])?></div>
        <?php unset($_SESSION['flash_erro']);
         endif; ?>
    <?php if ($_SESSION['perfil'] === 'admin'): ?>
        <hr>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id'] ?> </td>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td><?= ucfirst($usuario['perfil']) ?></td>
                            <td>
                                <a href="editar_usuario.php?id=<?= $usuario['id'] ?>", class="btn-editar" >Editar</a>
                                <?php if($usuario['id'] !== $admin_id_logado): ?>
                                    <form action="../app/controller/deletar_usuario.php" method="POST" style="display:inline" onsubmit="return confirm('Tem certeza?');"> 
                                        <input type="hidden" name="usuario_id" value="<?= $usuario['id']?>">
                                        <button type="submit", class="btn-deletar">
                                        Deletar
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif ?>
        <div class="btn-dashbox">
        <a href="<?= BASE_URL ?>/public/produto_listar.php" class="btn-manageproduct">Gerir Produtos</a>
        <a href="<?= BASE_URL ?>/public/logs.php" class="btn-auditrecord">Registros de Auditoria</a>
        </div>
        </div>
        <script src="<?= BASE_URL ?>/scripts/utils.js" defer></script>
        </body>
</html>