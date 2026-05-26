<?php
require_once 'conexao.php';
require_once 'EmailService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: esqueci_senha.php');
    exit;
}

$email = filter_input(INPUT_POST, 'email-recuperar', FILTER_VALIDATE_EMAIL);

if (!$email) {
    die('E-mail inválido.');
}


$stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE email = :email"); // Ve se o e-mail existe no bd
$stmt->execute([':email' => $email]);
$usuario = $stmt->fetch();

if ($usuario) { // cria o token já criptografado

    $token  = bin2hex(random_bytes(32)); // bin2hex para nao travar na URL e ficar mais facil de salvar no BD
    $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

    //invalida tokens antigos do mesmo e-mail
    $stmtInval = $pdo->prepare(
        "UPDATE reset_senha SET usado = 1 WHERE email = :email AND usado = 0"
    );

    $stmtInval->execute([':email' => $email]);

    $stmtIns = $pdo->prepare( //salva o novo token
        "INSERT INTO reset_senha (email, token, criacao, validade)
         VALUES (:email, :token, NOW(), :expira)"
    );

    $stmtIns->execute([
        ':email'  => $email,
        ':token'  => $token,
        ':expira' => $expira
    ]);


    $config = require __DIR__ . '/smtp_config.php';
    $link = $config['app_url'] . '/sistema/redefinir_senha.php?token=' . $token;
    //link de recuperação

    $email_service = new EmailService(); //envia o emial
    $enviou = $email_service->enviarRecuperacaoSenha($email, $usuario['nome'], $link);
    if ($enviou) {
        echo 'Recuperado com Sucesso';
    } else {
        echo 'Erro: Falha na conexão do SMTP no EmailService.';
    }
} else {
    echo 'Erro: E-mail não encontrado no banco de dados.';
}
