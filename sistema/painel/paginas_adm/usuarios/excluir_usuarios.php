<?php
require_once('../../conexao.php');
$id = @$_GET['id'];

try {

    $query = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $query->bindValue(':id', $id);
    $query->execute();

    echo "<script>window.location='../../index.php?pag=usuarios'</script>";
} catch (Exception $e) {


    echo "<script>alert('não foi possivel excluir: O cliente possui agendamentos vinculados.'); window.location='../../index.php?pag=usuarios';</script>";
}
