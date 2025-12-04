<?php 
require_once __DIR__ . '/../core/config.php';

header('Content-Type: application/json');

$pdo = conectar_banco();

$method = $_SERVER['REQUEST_METHOD'];

function response($status, $data = null, $code = 200) {
    http_response_code($code);
    echo json_encode(['status' => $status, 'data' => $data]);
    exit;
}


if($method === 'GET') {

//Busca produto por ID
if(isset($_GET['id'])) {
$id = (int) $_GET['id'];
$sql = 'SELECT p.*, c.nome AS categoria_nome FROM produtos P LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.id = :id LIMIT 1';
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$produto) {
     response('erro', 'Produto não encontrado,', 404);
}

response('ok', $produto);
}

//Busca por descrição/nome/categoria
if(isset($_GET['busca'])) {

    $busca = normalize($_GET['busca'] ?? '');
    $busca = strtolower($busca);

    $sql = 'SELECT p.*, c.nome AS categoria FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id 
    WHERE 
    LOWER(p.nome) LIKE :t1 
    OR LOWER(p.descricao) LIKE :t2 
    OR LOWER(c.nome) LIKE :t3
    ORDER BY p.nome ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':t1', "%$busca%", PDO::PARAM_STR);
    $stmt->bindValue(':t2', "%$busca%", PDO::PARAM_STR);
    $stmt->bindValue(":t3", "%$busca%", PDO::PARAM_STR);
    $stmt->execute();
    response('ok', $stmt->fetchAll(PDO::FETCH_ASSOC));

}


//Listar lançamentos
if(!isset($_GET['id'])  && $_GET['tipo'] === 'lancamento') {
    $stmt = $pdo->query('SELECT id, nome, preco, imagem_url, vendas FROM produtos ORDER BY data_lancamento DESC LIMIT 3');
    response('ok',  $stmt->fetchAll(PDO::FETCH_ASSOC));
}

//Listar mais vendidos
if(!isset($_GET['id']) && $_GET['tipo'] === 'mais_vendidos') {
    $stmt = $pdo->query('SELECT id, nome, preco, imagem_url, vendas FROM produtos ORDER BY vendas DESC LIMIT 3');
    response('ok', $stmt->fetchAll(PDO::FETCH_ASSOC));
}

//Listar produtos
if(!isset($_GET['id'])) {
  $sql = "SELECT p.*, c.nome AS categoria_nome
        FROM produtos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        ORDER BY p.nome ASC";


$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}

//Inserir produto 
if($method === 'POST') {
    $json = json_decode(file_get_contents('php://input'), true);

    if(!$json || !isset($json['nome']) || !isset($json['preco'])) {
        response('erro', 'Dados incompletos.', 400);
    }

$stmt = $pdo->prepare('INSERT INTO produtos (nome, preco, categoria_id, estoque, imagem_url) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([
    $json['nome'],
    $json['preco'],
    $json['categoria_id'] ?? null,
    $json['estoque'] ?? 0,
    $json['imagem_url'] ?? null
]);

response('ok', ['id' => $pdo->lastInsertId()], 201);
}


//Update produto
if($method === 'PUT') {

    if(!isset($_GET['id'])) {
        response('erro', 'ID não fornecido', 404);
    }

    $id = (int) $_GET['id'];
    $json = json_decode(file_get_contents('php://input'), true);

    $stmt = $pdo->prepare('UPDATE produtos SET nome = ?, preco = ?, categoria_id = ?, estoque = ?, imagem_url = ? WHERE id = ?');

    $stmt->execute([
        $json['nome'],
        $json['preco'],
        $json['categoria_id'],
        $json['estoque'],
        $json['imagem_url'],
        $id
    ]);

    response('ok', 'Produto Atualizado');
}

//Deletar Produto
if($method === 'DELETE') {
    if(!isset($_GET['id'])) {
        response('erro', 'ID não fornecido.', 400);
    }

    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare('DELETE FROM produtos WHERE id = ?');
    $stmt->execute([$id]);

    response('ok', 'Produto deletado.');
}

response('Erro', 'Método não suportado', 405);