<?php
$banco = 'barbearia';
$usuario = 'root';
$senha = '';
$servidor = 'localhost';

try {
    // Monta o DSN (Data Source Name) usando $servidor e $banco
    $dsn = "mysql:host=$servidor;dbname=$banco;charset=utf8";

    // Cria a conexão PDO usando $usuario e $senha
    $pdo = new PDO($dsn, $usuario, $senha);

    // Configura o PDO para mostrar erros caso algo dê errado
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Se der erro, ele cai aqui
    echo "Erro de conexão: " . $e->getMessage();
}
$nome_sistema = 'Barbearia Luiz';
$email_sistema = 'barbearia@outlook.com';
