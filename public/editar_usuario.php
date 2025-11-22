<?php
$perfil_exigido = 'admin';

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/verifica_sessao.php';

$usuario_id = $_GET['id'] ?? 0;
if(empty($usuario_id)) {
    redirecionar('../public/dashboard.php');
}

$pdo = conectar_banco();
$stmt = $pdo->prepare("SELECT id, nome, email, perfil FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

if(!$usuario) {
    $_SESSION['flash_erro'] = 'Usuário não encontrado.';
    redirecionar('../public/dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../assets/style.css">
        <title>Editar Usuário</title>
    </head>
    <body>
        <div class="tela-editar">
        <h2>Editar Usuário: <?= htmlspecialchars($usuario['nome'])?></h2>
        <form action="../app/atualizar_usuario.php" method="POST">

            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required> <br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email'])?>" required> <br><br>

            <label>Perfil</label><br>
            <select name="perfil">
                <option value="user" <?= ($usuario['perfil'] == 'user') ? 'selected' : '' ?> >User</option>
                <option value="admin" <?= ($usuario['perfil'] == 'admin') ? 'selected' : ''  ?> >Admin</option>
            </select> <br><br>

            <button type="submit">Atualizar Usuário</button>
            <a href="dashboard.php", class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </body>
</html>