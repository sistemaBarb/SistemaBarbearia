<?php
@session_start();
require_once("conexao.php");

// 1. Captura o valor digitado (aceitando o name como 'login' ou 'email' do seu HTML)
$login = trim($_POST['login'] ?? $_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// 2. Aplica a máscara caso o usuário digite apenas os 11 números do CPF
if (preg_match('/^[0-9]{11}$/', $login)) {
    $login = substr($login, 0, 3) . '.' . substr($login, 3, 3) . '.' . substr($login, 6, 3) . '-' . substr($login, 9, 2);
}

// 3. Faz a busca no banco (Email OU CPF)
$query = $pdo->prepare("SELECT * FROM usuarios WHERE (email = :login OR cpf = :login) AND senha = :senha");
$query->bindValue(":login", $login);
$query->bindValue(":senha", $senha);
$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

// 4. Lógica de redirecionamento e sessão
if (count($res) > 0) {
    $ativo = $res[0]['ativo'];

    if (strtolower($ativo) == 'sim') {
        session_regenerate_id(true);
        $_SESSION['id']    = $res[0]['id'];
        $_SESSION['nivel'] = $res[0]['nivel'];
        $_SESSION['nome']  = $res[0]['nome'];

        echo "<script>window.location='painel';</script>";
    } else {
        echo "<script>window.location='index.php?erro=inativo';</script>";
    }
} else {
    echo "<script>window.location='index.php?erro=login';</script>";
}
