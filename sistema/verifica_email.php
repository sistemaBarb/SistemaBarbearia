<?php
require_once 'conexao.php';

$mensagem = "";
$tipo_alerta = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT id FROM usuarios WHERE token_verificacao = :token LIMIT 1";
    $smt = $pdo->prepare($sql);
    $smt->execute([':token' => $token]);
    $usuario = $smt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $sql_update = "UPDATE usuarios SET email_verificado = 1, token_verificacao = NULL WHERE id = :id";
        $token_update = $pdo->prepare($sql_update);
        $token_update->execute([':id' => $usuario['id']]);

        $mensagem = "Seu e-mail está validado, sua conta está liberada.";
        $tipo_alerta = "alert-success";
    } else {
        $mensagem = "Token inválido ou já utilizado.";
        $tipo_alerta = "alert-danger";
    }
} else {
    $mensagem = "Nenhum token foi gerado ou informado.";
    $tipo_alerta = "alert-warning";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Verificação de E-mail</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
</head>

<body>

    <div class="container">
        <div class="row" style="margin-top: 100px;">
            <div class="col-md-6 col-md-offset-3 text-center">

                <div class="alert <?php echo $tipo_alerta; ?>">
                    <h4><?php echo $mensagem; ?></h4>
                </div>

                <br>
                <a href="index.php" class="btn btn-primary btn-lg">Ir para o Login</a>

            </div>
        </div>
    </div>

</body>

</html>