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

                        <form method="POST" action="../public/index.php?acao=cadastrar_cliente">


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

                            <div class="form-group" style="margin-top: 15px; margin-bottom: 20px; text-align: left;">
                                <div class="checkbox">
                                    <label style="font-size: 13px; color: #555;">
                                        <input type="checkbox" name="aceite_lgpd" required>
                                        Declaro que li e concordo com a coleta e uso dos meus dados pessoais conforme a
                                        <a href="#" data-toggle="modal" data-target="#LGPD" style="color: #337ab7; text-decoration: underline;">Política de Privacidade (LGPD)</a>.
                                    </label>
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

<div class="modal fade" id="LGPD" tabindex="-1" role="dialog" aria-labelledby="LGPD" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                <h4 class="modal-title" id="LGPD"></i> Política de Privacidade (LGPD)</h4>
            </div>

            <div class="modal-body" style="max-height: 400px; overflow-y: auto; text-align: justify; color: #555;">
                <p>Esta Política de Privacidade explica como a Barbearia coleta, usa, armazena e protege os seus dados pessoais, em conformidade com a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018).</p>

                <h5><strong>. Agentes de Tratamento e DPO</strong></h5>
                <p>A Barbearia atua como Controladora dos dados. O gestor do estabelecimento é o Encarregado de Proteção de Dados (DPO), responsável por atender às suas solicitações referentes à privacidade.</p>

                <h5><strong>. Quais dados coletamos e para quê?</strong></h5>
                <p>Coletamos estritamente o necessário para a prestação do serviço:</p>
                <ul>
                    <li><strong>Dados de Cadastro:</strong> Nome completo, CPF e E-mail (para identificação e agendamentos).</li>
                    <li><strong>Dados de Acesso:</strong> Senha criptografada de forma irreversível.</li>
                </ul>

                <h5><strong>. Retenção e Anonimização</strong></h5>
                <p>Seus dados permanecem ativos enquanto houver vínculo com a barbearia. Após 5 anos de inatividade, executamos a <strong>anonimização</strong> irreversível do seu cadastro, transformando as informações pessoais em dados estatísticos genéricos, garantindo sua total privacidade a longo prazo.</p>

                <h5><strong>. Compartilhamento e Segurança</strong></h5>
                <p>A Barbearia <strong>não vende, aluga ou compartilha</strong> seus dados com terceiros. Adotamos medidas rigorosas de segurança técnica, e nem mesmo os administradores do sistema têm acesso à sua senha original.</p>

                <h5><strong>. Quais são os seus direitos?</strong></h5>
                <p>De acordo com o Art. 18 da LGPD, você possui o direito de:</p>
                <ul>
                    <li>Confirmar a existência de tratamento dos seus dados;</li>
                    <li>Solicitar a correção de dados incompletos ou desatualizados;</li>
                    <li>Solicitar a exclusão definitiva (direito ao esquecimento) da sua conta e dos seus dados do nosso banco.</li>
                </ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Entendi e concordo</button>
            </div>

        </div>
    </div>
</div>

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

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>


</html>