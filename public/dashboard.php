<?php
$perfil_exigido = 'admin';

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/verifica_sessao.php';

$usuario_exib = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Admin', ENT_QUOTES, 'UTF-8');
$admin_id_logado = $_SESSION['usuario_id'];

$lista_usuarios = [];
$lista_produtos = [];

if($_SESSION['perfil'] === 'admin') {

    try {
        $pdo=conectar_banco();

        $stmt = $pdo->query('SELECT id, nome, email, perfil FROM usuarios ORDER BY nome ASC');
        $lista_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql_produtos = 'SELECT p.*, c.nome as categoria_nome
                         FROM produtos p
                         LEFT JOIN categorias c ON p.categoria_id = c.id
                         ORDER BY p.name ASC';
        $stmt_prod = $pdo->query($sql_produtos);
        $lista_produtos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

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
<body>
    <div class="tela-inicialadmin">
    <h1>Bem-vindo, <?= $usuario_exib ?></h1>
    <a href="../app/logout.php", class="btn-cancelar">Sair</a> <hr>
    <a href="<?= BASE_URL ?>/public/logs.php" style="margin-right: 15px;">Ver Logs de Auditoria</a>

    <h2>Gerenciamento de usuários</h2>

    <a href="criar_usuario.php", class="btn-criarusuario">
        + Adicionar Novo Usuário
    </a>

    
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
                                    <form action="../app/deletar_usuario.php" method="POST" style="display:inline" onsubmit="return confirm('Tem certeza?');"> 
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
        </div>

        <h2>Gerir Produtos</h2>
        <a href="<?= BASE_URL ?>/public/produto_form.php">Adicionar Novo Produto</a>
        <table>
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lista_produtos)): ?>
                <tr>
                    <td colspan="6">Nenhum produto cadastrado.</td>
                </tr>
                 <?php else: ?>        
                    <?php foreach($lista_produtos as $produto): ?>
                        <tr>
                            <td>
                                <?php if(!empty($produto['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($produto['image_url'])?>" alt="Prod">
                                <?php else: ?>
                                    <span>Sem img</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($produto['nome']) ?></td>
                            <td><?= htmlspecialchars($produto['categoria_nome'] ?? 'Sem categoria') ?></td>
                            <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                            <td><?= $produto['estoque'] ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/public/produto_form.php?id=<?=$produto['id']?>">Editar</a>

                                <form action="<?= BASE_URL ?>/app/deletar_produto.php" method="POST" display="inline" onsubmit="return confirm('Tem certeza que deseja excluir esse produto?');">
                                    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                                    <button type="submit">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>            
            </tbody>
        </table>
</body>
</html> 