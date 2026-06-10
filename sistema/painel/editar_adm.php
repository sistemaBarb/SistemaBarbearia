<?php
@session_start();
require_once('../../config/database.php');
$database = new Database();
$pdo = $database->getConnection();

$id_logado = $_SESSION['id'];
$nome  = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

try {
    if (empty($senha)) {
        $return = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id");
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $return = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha_cr = :senha WHERE id = :id");
        $return->bindValue(":senha", $senha_hash);
    }

    $return->bindValue(":nome", $nome);
    $return->bindValue(":email", $email);
    $return->bindValue(":id", $id_logado);
    $return->execute();
    $_SESSION['nome'] = $nome;

    echo "<script>alert('atualizado com sucesso!'); window.location='index.php';</script>";
} catch (PDOException $e) {
    echo "<script>alert('Erro ao atualizar perfil: " . $e->getMessage() . "'); window.location='index.php';</script>";
}
