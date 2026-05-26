<?php
require_once 'conexao.php';

$token = $_GET['token'] ?? '';

if (empty($token)) { // Verifica se o token é válido, não foi usado e não expirou
    die('Token não informado.');
}


$stmt = $pdo->prepare(
    "SELECT email FROM reset_senha
     WHERE token = :token AND usado = 0 AND validade > NOW()" //now() serve para o banco capturar a data e hora da geração do token
);
$stmt->execute([':token' => $token]);
$registro = $stmt->fetch();

if (!$registro) {
    die('Link inválido ou expirado. Solicite uma nova recuperação.');
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Nova senha</title>
</head>

<body>
    <h1>Sua nova senha</h1>
    <form action="nova_senha.php" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="senha">Nova senha (minimo 8 caracteres):</label><br>
        <input type="password" name="senha" id="senha" required minlength="8"><br><br>

        <label for="confirma">Confirmar senha</label><br>
        <input type="password" name="confirma" id="confirma" required minlength="8"><br><br>

        <button type="submit">Salvar</button>
    </form>
</body>

</html>
```