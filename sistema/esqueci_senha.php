<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Esqueci minha senha</title>
</head>

<body>
    <h1>Recuperar senha</h1>
    <form action="../public/index.php?acao=solicitar_recuperacao" method="POST">
        <label for="email">Digite seu e-mail cadastrado:</label><br>
        <input type="email" name="email" id="email" required>
        <br><br>
        <button type="submit">Enviar link de recuperação</button>
    </form>
</body>

</html>