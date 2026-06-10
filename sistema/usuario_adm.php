<?php //Pagina para sempre ter um usuario adm criado no banco de dados
require_once '../config/database.php';
$database = new Database();
$pdo = $database->getConnection();

try {
    $verifica_admin = $pdo->query("SELECT id FROM usuarios WHERE nivel = 'administrador'");

    if ($verifica_admin->rowCount() == 0) {

        // Dados padrão do Administrador Mestre
        $nome_admin  = 'luiz';
        $email_admin = 'barbearialuiz1@outlook.com';
        $cpf_admin   = '00000000000';
        $senha_texto = '12345678';
        $senha_hash  = password_hash($senha_texto, PASSWORD_DEFAULT); // Criptografia segura PDO
        $nivel_admin = 'administrador';
        $ativo_admin = 'sim';
        $data_hoje   = date('Y-m-d');


        $sql = "INSERT INTO usuarios (nome, email, cpf, senha_cr, nivel, ativo, data_cadastro) 
                       VALUES (:nome, :email, :cpf, :senha, :nivel, :ativo, :data)";

        $inserir_admin = $pdo->prepare($sql);
        $inserir_admin->bindParam(':nome', $nome_admin);
        $inserir_admin->bindParam(':email', $email_admin);
        $inserir_admin->bindParam(':cpf', $cpf_admin);
        $inserir_admin->bindParam(':senha', $senha_hash);
        $inserir_admin->bindParam(':nivel', $nivel_admin);
        $inserir_admin->bindParam(':ativo', $ativo_admin);
        $inserir_admin->bindParam(':data', $data_hoje);
        $inserir_admin->execute();

        echo " O usuário Administrador Master foi criado!";
        echo "<hr><a href='index.php'>Voltar para o Login</a>";
    } else {
        echo "já possui um usuário administrador ativo</p>";
        echo "<hr><a href='index.php'>Voltar para o Login</a>";
    }
} catch (PDOException $e) {
    echo "<pErro ao semear o banco de dados:" . $e->getMessage() . "</p>";
}
