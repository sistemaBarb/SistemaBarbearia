<?php
$token = $_GET['token'] ?? '';

if (empty($token)) {
    echo "<script>window.alert('Link de recuperação inválido'); window.location='index.php';</script>";
    exit;
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
    <form action="../public/index.php?acao=redefinir_senha" method="POST">
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