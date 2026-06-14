=
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Barbearia</title>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="CSS/login.css">


    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
</head>

<body>

    <div class="container">

        <div class="row" style="margin-top:100px">
            <div class=" col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3" style="opacity:0.8" sytle="border-radius:20px">
                <div class="caixa-login">

                    <form action="../public/index.php?acao=logar" method="post">
                        <fieldset>
                            <h2>Faça seu Login</h2>
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
                                } elseif ($_GET['erro'] == 'nao_verificado') {
                                    echo '<div class="alert alert-info text-center" role="alert">
                                  Seu e-mail ainda não foi verificado. Verifique sua caixa de entrada!
                                 </div>';
                                }
                            }
                            ?>



                            <div class="form-group">
                                <input type="text" name="login" id="login" class="form-control input-lg" placeholder="Email ou CPF">
                            </div>

                            <div class="form-group">
                                <input type="password" name="senha" id="password" class="form-control input-lg" placeholder="Senha" minlength="8" required>
                            </div>

                            <a href="" data-toggle="modal" data-target="#exampleModal" class="btn btn-link pull-right">Esqueceu sua senha ?</a>

                            <hr class="colorgraph">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-12">
                                    <input type="submit" class="btn btn-lg btn-success btn-block" value="Entrar">
                                </div>

                            </div>
                            <div class="text-center" style="margin-top: 15px;">
                                <p>Não tem uma conta?<a href="cadastro.php" style="color: #337ab7; font-weight: bold;">Cadastre-se aqui ! </a></p>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

        </div>

</body>

</html>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content" style="width: 450px">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Recuperar Senha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form method="post" id="recuperar-senha">
                <div class="modal-body">
                    <p>Digite seu e-mail que enviaremos um link para definir uma nova senha</p>

                    <input id="email-rec" placeholder="Digite seu email cadastrado" class=" form-control" type="email" name="email-recuperar" required>

                    <div id="msg-recuperar" text-align="center">


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Recuperar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script type="text/javascript">
    $("#recuperar-senha").submit(function(event) {
        event.preventDefault(); // Impede a página de recarregar 

        let botao = $(this).find('button[type="submit"]');
        let textoOriginal = botao.text();
        botao.text('Enviando...');
        botao.prop('disabled', true);

        var formData = new FormData(this); // Pega os dados do formulário

        $.ajax({
            url: "processar_recuperacao.php", // URL corrigida
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#msg-recuperar').text('');
                $('#msg-recuperar').removeClass('text-success text-danger');

                if (mensagem.trim() == "Recuperado com Sucesso") {
                    $('#email-rec').val('');

                    $('#msg-recuperar').addClass('text-success');

                    $('#msg-recuperar').text('Sua senha foi enviada para seu e-mail!');
                } else {
                    $('#msg-recuperar').addClass('text-danger');

                    $('#msg-recuperar').text(mensagem);
                }
            },
            complete: function() {
                botao.text(textoOriginal);
                botao.prop('disabled', false);
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
</script>