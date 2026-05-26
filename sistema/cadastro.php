<?php
require_once("conexao.php");

if (isset($_POST['email']) && isset($_POST['cpf'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];


    if ($senha !== $confirma_senha) {    // Ve se as senhas são diferentes
        $erro_senha = "As senhas não são iguais!";
    } else {

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $cpf = trim($_POST['cpf']);

        if (preg_match('/^[0-9]{11}$/', $cpf)) {
            $cpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
        }

        $token = bin2hex(random_bytes(32));

        $sql = "INSERT INTO usuarios (nome, email, cpf, senha_cr, nivel, ativo, token_verificacao, email_verificado)
                VALUES (:nome, :email, :cpf, :senha, 'cliente', 'sim', :token, 0)";

        try {
            $cons_bd = $pdo->prepare($sql);
            $cons_bd->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':cpf' => $cpf,
                ':senha' => $senha_hash,
                ':token' => $token
            ]);

            $link_verificacao = "http://localhost/barbearia/sistema/verifica_email.php?token=" . $token;

            echo "<div class='alert alert-success text-center'>Cadastro feito com sucesso! <br> <a href='$link_verificacao'>Clique aqui para confirmar sua conta</a></div>";
        } catch (PDOException $erro) {
            echo "<div class='alert alert-danger text-center'>Erro ao cadastrar usuario: " . $erro->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
</head>

<body>

    <div class="container">
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Criar nova conta</h3>
                    </div>
                    <div class="panel-body">

                        <?php if (isset($erro_senha)) { ?>
                            <div class="alert alert-danger text-center" style="background-color: #f2dede; color: #a94442; border-color: #ebccd1; margin-bottom: 15px;">
                                <?php echo $erro_senha; ?>
                            </div>
                        <?php } ?>

                        <form method="POST" action="cadastro.php">
                            <div class="form-group">
                                <label>Nome Completo</label>
                                <input type="text" name="nome" class="form-control" required placeholder="Digite seu nome">
                            </div>

                            <div class="form-group">
                                <label>CPF</label>
                                <input type="text" name="cpf" class="form-control" required placeholder="Digite seu CPF (apenas números ou completo)" maxlength="14">
                            </div>

                            <div class="form-group">
                                <label>E-mail</label>
                                <input type="email" name="email" class="form-control" required placeholder="Digite seu e-mail">
                            </div>
                            <div class="form-group">
                                <label>Senha</label>
                                <div class="input-group">
                                    <input type="password" name="senha" id="senha" class="form-control" required placeholder="Crie uma senha forte">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" onclick="mostrarSenha('senha', 'icon-senha')">
                                            <i id="icon-senha" class="glyphicon glyphicon-eye-open"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Confirme sua Senha</label>
                                <div class="input-group">
                                    <input type="password" name="confirma_senha" id="confirma_senha" class="form-control" required placeholder="Confirme sua senha">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" onclick="mostrarSenha('confirma_senha', 'icon-confirma')">
                                            <i id="icon-confirma" class="glyphicon glyphicon-eye-open"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-block">Finalizar Cadastro</button>

                            <div class="text-center" style="margin-top: 15px;">
                                <a href="index.php">caso já tenha conta, Faça o login aqui</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    function mostrarSenha(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("glyphicon-eye-open");
            icon.classList.add("glyphicon-eye-close");
        } else {
            input.type = "password";
            icon.classList.remove("glyphicon-eye-close");
            icon.classList.add("glyphicon-eye-open");
        }
    }
</script>

</html>