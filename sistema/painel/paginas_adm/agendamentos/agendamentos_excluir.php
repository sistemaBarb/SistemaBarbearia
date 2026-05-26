<?php
require_once('../../../conexao.php');

$id = $_GET['id'];

$query = $pdo->query("DELETE FROM agendamentos WHERE id = '$id'");


echo "<script>window.location='../../index.php?pag=agendamentos'</script>";
