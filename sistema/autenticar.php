<?php
@session_start();
require_once("conexao.php");

//analisa o que o usuario digitou no login 
$login = trim($_POST['login'] ?? $_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

//aplica a máscara para o usuario digitar o cpf como ele quiser com ou sem traço e ponto
if (preg_match('/^[0-9]{11}$/', $login)) {
    $login = substr($login, 0, 3) . '.' . substr($login, 3, 3) . '.' . substr($login, 6, 3) . '-' . substr($login, 9, 2);
}

$query = $pdo->prepare("SELECT * FROM usuarios WHERE (email = :email OR cpf = :cpf)");
$query->bindValue(":email", $login);
$query->bindValue(":cpf", $login);
$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) > 0) { // vai ver se encontrou um usuario na busca


    if (password_verify($senha, $res[0]['senha_cr'])) { //ira verificar a senha 

        $ativo = $res[0]['ativo']; //vera no banco de dados se a situação do usuario está ativo, e fara a linha de codígo asseguir 
        $email_verificado = $res[0]['email_verificado']; // Puxa o status de verificação

        // Barra o acesso se o e-mail não estiver verificado
        if ($email_verificado == 0) {
            echo "<script>window.location='index.php?erro=nao_verificado';</script>";
            exit();
        }

        if (strtolower($ativo) == 'sim') {
            session_regenerate_id(true);
            $_SESSION['id'] = $res[0]['id'];
            $_SESSION['nivel'] = $res[0]['nivel'];
            $_SESSION['nome'] = $res[0]['nome'];

            echo "<script>window.location='painel';</script>";
        } else {
            echo "<script>window.location='index.php?erro=inativo';</script>";
        }
    } else {

        echo "<script>window.location='index.php?erro=login';</script>"; // se a senha tiver errada vai para cá
    }
} else {
    echo "<script>window.location='index.php?erro=login';</script>";
}
