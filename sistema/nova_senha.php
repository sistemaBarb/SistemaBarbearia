<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: esqueci_senha.php');
    exit;
}

$token    = $_POST['token']    ?? '';
$senha    = $_POST['senha']    ?? '';
$confirma = $_POST['confirma'] ?? '';


if (empty($token) || empty($senha)) { // Validação básicas para o sistema 
    die('Dados incompletos.');
}
if ($senha !== $confirma) {
    die('As senhas não coincidem.');
}
if (strlen($senha) < 8) {
    die('A senha deve ter no mínimo 8 caracteres.');
}


$stmt = $pdo->prepare( // valida o token
    "SELECT email FROM reset_senha
     WHERE token = :token AND usado = 0 AND validade > NOW()"
);
$stmt->execute([':token' => $token]);
$registro = $stmt->fetch();

if (!$registro) {
    die('Link inválido ou expirado.');
}

$email = $registro['email'];

$hash = password_hash($senha, PASSWORD_DEFAULT); // criptografa a nova senha 



$pdo->beginTransaction();
try {
    $stmtUp = $pdo->prepare("UPDATE usuarios SET senha_cr = :senha WHERE email = :email");
    $stmtUp->execute([
        ':senha' => $hash,
        ':email' => $email
    ]); // Atualiza a senha do usuário


    $stmtTok = $pdo->prepare("UPDATE reset_senha SET usado = 1 WHERE token = :token");
    $stmtTok->execute([':token' => $token]); // Marca que o token já foi utilizado

    $pdo->commit();

    echo 'Senha redefinida com sucesso! <a href="index.php">Faça login</a>';
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log('[NovaSenha] ' . $e->getMessage());
    die('Erro ao atualizar a senha. Tente novamente.');
}
