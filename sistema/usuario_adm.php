<?php
require_once("conexao.php");
try {
    $senha = "12345678";
    $senha_crip = password_hash($senha, PASSWORD_DEFAULT);

    $query = $pdo->query("SELECT * from usuarios where nivel ='administrador'");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    if (count($res) == 0) {
        $sql = "INSERT INTO usuarios SET nome='luiz', email='$email_sistema', cpf='00000000000',senha_cr=:senha, nivel= 'administrador', data_cadastro= curDate(), ativo='sim', foto='sem-foto.jpg', telefone='', endereco='', email_verificado='1'";

        $cripto = $pdo->prepare($sql);

        $cripto->execute([
            ':senha' => $senha_crip
        ]);
        echo "usuário adm foi criado com sucesso";
        echo "<hr><a href='index.php'>voltar para o Login</a>";
    } else {
        echo "já existe um usuario adm criado.";
        echo "<hr><a href='index.php'>voltar para o Login</a>";
    }
} catch (PDOException $erro) {
    echo "Erro ao configurar: " . $erro->getMessage();
}
