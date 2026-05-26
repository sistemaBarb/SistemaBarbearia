<?php
require_once("../../../conexao.php");

$id = $_POST['id'];

try {
    $sql = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $sql->execute([':id' => $id]);

    echo "deletado com Sucesso";
} catch (PDOException $e) {
    echo "erro ao tentar excluir: " . $e->getMessage();
}
