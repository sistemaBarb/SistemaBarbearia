<?php
require_once("../../../conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];

$query = $pdo->prepare("SELECT * FROM usuarios WHERE (email = :email OR cpf = :cpf) AND id != :id");
$query->execute([':email' => $email, ':cpf' => $cpf, ':id' => $id]);
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) > 0) {
    echo "email ou CPF está cadastrado em outro usuário";
    exit();
}

try {
    $sql = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone WHERE id = :id");
    $sql->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':cpf' => $cpf,
        ':telefone' => $telefone,
        ':id' => $id
    ]);

    echo "editado com Sucesso";
} catch (PDOException $e) {
    echo "erro ao editar: " . $e->getMessage();
}
