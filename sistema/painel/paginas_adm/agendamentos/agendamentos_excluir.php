<?php
require_once('../../../conexao.php');

$id = $_GET['id'];

$query = $pdo->query("DELETE FROM agendamentos WHERE id = '$id'");

// Retorna para pagina de agendamentos padrão
echo "<script>window.location='../../index.php?pag=agendamentos'</script>";
