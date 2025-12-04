<?php
$perfil_exigido = 'admin';

require_once __DIR__ . '/../app/core/config.php';
require_once __DIR__ . '/../app/core/verifica_sessao.php';

$usuario_exib = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Admin', ENT_QUOTES, 'UTF-8');

$logs = [];

try {
    $pdo = conectar_banco();
    
   $sql = 'SELECT l.*, u.nome AS usuario_nome, u.email AS usuario_email
            FROM user_logs l
            LEFT JOIN usuarios u ON l.usuario_id = u.id
            ORDER BY l.data_hora DESC
            LIMIT 50';
    
    $stmt = $pdo->query($sql);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e)  {
    $erro_bd = 'Erro ao carregar logs';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="logs-page">

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
                <a href="#male">MASCULINO</a>
                <a href="#female">FEMININO</a>
                <a href="#about">CONTATO</a>
            </nav>

            <!-- ================= ICONS / PROFILE ================= -->
            <div class="icons">
                <a href="#">
                    <img width="35" height="35" class="search" src="https://img.icons8.com/ios-filled/50/search--v1.png" alt="search--v1"/>
                </a>

                <a href="#">
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
    <div class="tela-logs">
    <h1>Logs de auditoria</h1>
    <p>Histórico das últimas 100 ações do sistema</p>

    <a href="<?= BASE_URL ?>/public/dashboard.php">Voltar ao Dashboard</a>

    <?php if(isset($erro_bd)): ?>
        <?= $erro_bd ?>
    <?php endif; ?>
    <div class="logs-table-container">
    <table>
        <thead>
            <tr>
                <th>Data/hora</th>
                <th>Usuário</th>
                <th>Ação</th>
                <th>Detalhes</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Nenhum registro encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <?php
                        $classe_badge = 'badge-login';
                        $acao = strtolower($log['acao']);
                        if (strpos($acao, 'logout') !== false) $classe_badge = 'badge_logout';
                        elseif (strpos($acao, 'criar') !== false) $classe_badge = 'badge-criar';
                        elseif (strpos($acao, 'atualizar') !== false) $classe_badge = 'badge-atualizar';
                        elseif (strpos($acao, 'deletar') !== false) $classe_badge = 'badge-deletar';
                        elseif (strpos($acao, 'reset') !== false) $classe_badge = 'badge-atualizar';
                    ?>
                    <tr>
                        <td><?= date('d/m/Y H:i:s', strtotime($log['data_hora'])) ?></td>
                        <td>
                            <?php if($log['usuario_nome']): ?>
                                <strong><?= htmlspecialchars($log['usuario_nome']) ?></strong><br>
                                <span><?= htmlspecialchars($log['usuario_email']) ?></span>
                            <?php else: ?>
                                <span>(Usuario não logado/removido)</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $classe_badge ?>">
                                <?= htmlspecialchars($log['acao']) ?>
                            </span>
                        </td>
                        <td>
                            <?= htmlspecialchars($log['detalhes'] ?? '-') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
        </tbody>
    </table>
    </div>
    </div>
    <script src="<?= BASE_URL ?>/scripts/utils.js" defer></script>
</body>
</html>