<?php
@session_start();
require_once('../../../conexao.php');

$id = $_POST['id'];
$data = $_POST['data'];
$hora = $_POST['hora'];
$cliente = $_POST['cliente'];
$funcionario = $_POST['funcionario'];
$observacao = $_POST['observacao'];

$data_lancamento = date('Y-m-d');
$usuario_lancamento = $_SESSION['id'];

if ($id == "") {
    $query = $pdo->prepare("INSERT INTO agendamentos SET funcionario = :funcionario, cliente = :cliente, data = :data, hora = :hora, observacao = :observacao, data_lancamento = :data_lancamento, usuario_lancamento = :usuario_lancamento");
} else {
    $query = $pdo->prepare("UPDATE agendamentos SET funcionario = :funcionario, cliente = :cliente, data = :data, hora = :hora, observacao = :observacao WHERE id = :id");
    $query->bindValue(":id", $id);
}

$query->bindValue(":funcionario", $funcionario);
$query->bindValue(":cliente", $cliente);
$query->bindValue(":data", $data);
$query->bindValue(":hora", $hora);
$query->bindValue(":observacao", $observacao);

if ($id == "") {
    $query->bindValue(":data_lancamento", $data_lancamento);
    $query->bindValue(":usuario_lancamento", $usuario_lancamento);
}

$query->execute();


echo "<script>window.location='../../index.php?pag=agendamentos'</script>";
