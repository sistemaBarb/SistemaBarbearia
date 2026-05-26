<?php
require_once('../../conexao.php');

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$ativo = $_POST['ativo'];

try {
    $query = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, ativo = :ativo WHERE id = :id");
    $query->bindValue(':nome', $nome);
    $query->bindValue(':email', $email);
    $query->bindValue(':cpf', $cpf);
    $query->bindValue(':ativo', $ativo);
    $query->bindValue(':id', $id);
    $query->execute();

    echo "<script>alert('Cliente atualizado com sucesso!'); window.location='../../index.php?pag=usuarios';</script>";
} catch (Exception $e) {
    echo "<script>alert('Erro ao atualizar!'); window.location='../../index.php?pag=usuarios';</script>";
}
