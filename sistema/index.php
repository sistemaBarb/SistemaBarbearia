<?php
require_once("conexao.php");


$senha = "123";
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);

$query = $pdo->query("SELECT * from usuarios where nivel ='administrador'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) == 0) {
    $pdo->query("INSERT INTO usuarios SET nome='luiz', email='$email_sistema', cpf='000.000.000-00', senha='$senha', senha_cr='$senha_crip', nivel= 'administrador', data_cadastro= curDate(), ativo='sim', foto='sem-foto.jpg'");
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nome_sistema; ?></title>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="CSS/login.css">
</head>

<body>

    <div class="container">

        <div class="row" style="margin-top:20px">
            <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">

                <form role="form" action="autenticar.php" method="post">
                    <fieldset>
                        <h2>Please Sign In</h2>
                        <hr class="colorgraph">
                        <?php
                        if (isset($_GET['erro'])) {

                            // Verifica se o erro é de login/senha
                            if ($_GET['erro'] == 'login') {
                                echo '<div class="alert alert-danger text-center" role="alert">
                                 Usuário ou senha incorretos!
                              </div>';
                            }

                            // Verifica se o erro é de usuário inativo
                            elseif ($_GET['erro'] == 'inativo') {
                                echo '<div class="alert alert-warning text-center" role="alert">
                              Seu usuário não está ativo. Contate o administrador!
                             </div>';
                            }
                        }
                        ?>



                        <div class="form-group">
                            <input type="text" name="login" id="login" class="form-control input-lg" placeholder="Email ou CPF">
                        </div>

                        <div class="form-group">
                            <input type="password" name="senha" id="password" class="form-control input-lg" placeholder="Senha">
                        </div>

                        <a href="" class="btn btn-link pull-right">Forgot Password?</a>
                        </span>
                        <hr class="colorgraph">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Sign In">
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <a href="" class="btn btn-lg btn-primary btn-block">Register</a>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

    </div>

</body>

</html>