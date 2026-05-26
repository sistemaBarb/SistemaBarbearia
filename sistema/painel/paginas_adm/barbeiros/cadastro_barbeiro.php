<?php
require_once("../../../conexao.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];

$senha_crip = password_hash($senha, PASSWORD_DEFAULT);


$query = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email OR cpf = :cpf");
$query->execute([
    ':email' => $email,
    ':cpf' => $cpf
]);
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) > 0) {
    echo "email ou cpf já está cadastrado";
    exit();
}

try {
    $sql = "INSERT INTO usuarios SET nome=:nome, email=:email, cpf=:cpf, senha_cr=:senha, nivel='barbeiro', data_cadastro=curDate(), ativo='sim', foto='sem-foto.jpg', telefone=:telefone, endereco='', email_verificado='1'";

    $inserir = $pdo->prepare($sql);

    $inserir->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':cpf' => $cpf,
        ':senha' => $senha_crip,
        ':telefone' => $telefone
    ]);

    echo "salvo";
} catch (PDOException $e) {
    echo "erro ao salvar: " . $e->getMessage();
}
