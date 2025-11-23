<?php
$perfil_exigido = 'admin';

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/verifica_sessao.php';



$usuario_exib = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Admin', ENT_QUOTES, 'UTF-8');

$logs = [];

try {
    $pdo = conectar_banco();
    
   $sql = 'SELECT l.*, u.nome AS usuario_nome, u.email AS usuario_email
            FROM user_logs l
            LEFT JOIN usuarios u ON l.usuario_id = u.id
            ORDER BY l.data_hora DESC
            LIMIT 100';
    
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
    <link rel="stylesheets" href="#">
</head>
<body>
    <h1>Logs de auditoria</h1>
    <p>Histórico das últimas 100 ações do sistema</p>

    <a href="<?= BASE_URL ?>/public/dashboard.php">Voltar ao Dashboard</a>

    <?php if(isset($erro_bd)): ?>
        <?= $erro_bd ?>
    <?php endif; ?>

    <table>
        <thead>
            <tr>Data/hora</tr>
            <tr>Usuário</tr>
            <tr>Ação</tr>
            <tr>Detalhes</tr>
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
</body>
</html>