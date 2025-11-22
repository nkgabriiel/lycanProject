<?php
$perfil_exigido = 'admin';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/verifica_sessao.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('../public/dashboard.php');
}

$id = $_POST['id'] ?? 0;
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ??'');
$perfil = $_POST['perfil'] ?? 'user';

if (empty($id) || empty($nome) || empty($email)) {
    $_SESSION['flash_erro'] = 'Dados inválidos.';
    redirecionar('../public/dashboard.php');
}

if($id == $_SESSION['usuario_id'] && $perfil == 'user') {
    $_SESSION['flash_erro'] = 'Você não pode remover seu status de administrador.';
    redirecionar('../public/editar_usuario.php?id=' . $id);
}

$pdo = conectar_banco();

try {
   $sql = "UPDATE usuarios SET nome = ?, email = ?, perfil = ? WHERE id = ?";
   $stmt = $pdo->prepare($sql);

    if($stmt->execute([$nome, $email, $perfil, $id])) {
   $_SESSION['flash_sucesso'] = "Usuário atualizado com sucesso!";
    } else {
        $_SESSION['flash_erro'] = 'Ocorreu uma falha ao executar a atualização.';
        redirecionar('../public/editar_usuario.php?id='. $id);
    }

} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        $_SESSION['flash_erro'] = 'Este email já está em uso por outra conta.';
        redirecionar('../public/editar_usuario.php?id='. $id);
    } else {
        $_SESSION['flash_erro'] = 'Erro ao atualizar usuário: '. $e->getMessage();
        redirecionar('../public/editar_usuario.php?id='. $id);
    }   
}
redirecionar('../public/dashboard.php');