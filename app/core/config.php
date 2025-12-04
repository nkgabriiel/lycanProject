<?php

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie.httponly', 1);
    ini_set('session.use.strict.mode', 1);

    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie.secure', 1);
    }

    session_start();
}

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'sistema_auth';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';
$DB_PORT = getenv('DB_PORT') ?: '3306';

define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/lycanproject');

function conectar_banco() {
    global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS, $DB_PORT;

    $dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";

    try {
        return new PDO($dsn, $DB_USER, $DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    } catch (PDOException $e) {
        die("Erro de conexão: " . $e->getMessage());
    }
}

function redirecionar($caminho_absoluto) {
    // Garante que o caminho comece com /
    if (substr($caminho_absoluto, 0, 1) !== '/') {
        $caminho_absoluto = '/' . $caminho_absoluto;
    }
    header("Location: " . BASE_URL . $caminho_absoluto);
    exit;
}

function registrarLog($acao, $detalhes = null) {
    try {
        $pdo = conectar_banco();
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        $stmt = $pdo->prepare("INSERT INTO user_logs (usuario_id, acao, detalhes, data_hora) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$usuario_id, $acao, $detalhes]);
        
    } catch (Exception $e) {
        error_Log("Erro ao registrar log: " . $e->getMessage());
    }
}
    function sanitizar($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }

    function obterUserLogado() {
        return $_SESSION['usuario'] ?? null;
    }

    function generateToken(int $length = 32):string {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }

    function csrf_generate() {
        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    function csrf_validate($token_enviado) {
        if(empty($_SESSION['csrf_token']) || empty($token_enviado) || !hash_equals($_SESSION['csrf_token'], $token_enviado)) {
            return false;
        }
        return true;
    }

    function normalize($string) {
        $normal = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        return strtolower($normal);
    }

    function baseTerm($palavra) {
        return rtrim($palavra, "sS");
    }


    function validarForcaSenha($senha) {
        $erros = [];
         if(strlen($senha)<8) $erros[]='Senha com mínimo de 8 caracteres';
         if (!preg_match('/[A-Z]/',$senha)) $erros[]='Uma letra maiúscula';
         if (!preg_match('/[a-z]/',$senha)) $erros[]='Uma letra minúscula';
         if (!preg_match('/[0-9]/',$senha)) $erros[]='Um número';
         if (!preg_match('/[^A-Za-z0-9]/',$senha)) $erros[]='Um caractere especial';
    return ['valida'=>empty($erros),'erros'=>$erros];
    }

    $pdo = conectar_banco();

?>