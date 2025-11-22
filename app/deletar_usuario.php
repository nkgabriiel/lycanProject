<?php
$perfil_exigido = 'admin';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/verifica_sessao.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['usuario_id'])) {
    redirecionar('../public/dashboard.php');
}

$id_delete = $_POST['usuario_id'];
$admin_id_logado = $_SESSION['usuario_id'];

if($id_delete == $admin_id_logado) {
    $_SESSION['flash_erro'] = 'Você não pode deletar a sua própria conta!';
    redirecionar('../public/dashboard.php');
}

try {
    $pdo = conectar_banco();
    $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = ?');
    $stmt->execute([$id_delete]);

    $_SESSION['flash_sucesso'] = 'Usuário deletado com sucesso!';
} catch (PDOException $e) {
    $_SESSION['flash_erro'] = 'Erro ao deletar usuário.';
}

redirecionar('../public/dashboard.php');
?>