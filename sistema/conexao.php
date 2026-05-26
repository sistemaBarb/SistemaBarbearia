<?php
$banco = 'barbearia';
$usuario = 'root';
$senha = '';
$servidor = 'localhost';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // facilita os selects
    PDO::ATTR_EMULATE_PREPARES   => false,                  // melhora segurança e tipagem
];

try {

    $dsn = "mysql:host=$servidor;dbname=$banco;charset=utf8mb4";

    $pdo = new PDO($dsn, $usuario, $senha, $options);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
    exit;
}


$nome_sistema = 'Barbearia Luiz';
$email_sistema = 'barbearialuiz1@outlook.com';
