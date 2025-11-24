<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/verifica_sessao.php';

$erro = $_SESSION['flash_erro'] ?? '';
$sucesso = $_SESSION['flash_sucesso'] ?? '';
unset($_SESSION['flash_erro'], $_SESSION['flash_sucesso']);

try {
    $pdo = conectar_banco();
    $stmt = $pdo->prepare('SELECT nome, email FROM usuarios WHERE id = ?');
    $stmt->execute([$_SESSION['usuario_id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$usuario) {
        redirecionar('/app/logout.php');
    }
} catch (PDOException $e) {
    die('Erro ao carregar perfil.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
</head>
<body>
    <h2>Meu Perfil</h2>
    <?php if($_SESSION['perfil'] === 'admin'): ?>
        <a href='<?= BASE_URL ?>/public/dashboard.php'>Voltar</a>
    <?php else: ?>
        <a href='<?= BASE_URL ?>/public/pagina_inicial.php'>Voltar</a>
    <?php endif; ?>

    <?php if($erro): ?> <div style="color:red; margin-bottom:15px;"><?= $erro?></div><?php endif; ?>
    <?php if($sucesso): ?> <div style="color:green; margin-bottom: 15px;"><?= $sucesso ?> </div><?php endif; ?>

    <form action="<?= BASE_URL ?>/app/atualizar_meu_perfil.php" method="POST">
        <h3>Dados Pessoais</h3>

        <label for="nome">Nome:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required><br><br>

        <button type="submit">Salvar Dados</button>
    </form>

    <form action="<?= BASE_URL ?>/app/mudar_senha.php" method="POST">
        <h3>Alterar Senha</h3>

        <label for="senha_atual">Senha atual (Obrigatório):</label><br>
        <input type="password" name="senha_atual" required><br><br>

        <label for="nova_senha">Nova senha:</label>
        <input type="password" name="nova_senha" required><br><br>

        <label for="confirmar_senha">Confirmar senha:</label>
        <input type="password" name="confirmar_senha" required><br><br>

        <button type="submit">Atualizar senha</button>
    </form>
</body>
</html>
