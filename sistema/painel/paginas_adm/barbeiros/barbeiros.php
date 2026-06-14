<?php
global $nivel_usuario;

if ($nivel_usuario != 'administrador') {
    echo "<div class='alert alert-danger mt-4 text-center'>
            <strong>Acesso Negado!</strong> Você não tem permissão para acessar esta página.
          </div>";
    exit();
}
?>

<div class="row mb-3">...



    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary btn-flat btn-pri" data-toggle="modal" data-target="#modalCadastrarBarbeiro">
                Novo Barbeiro
            </button>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>nome</th>
                        <th>email</th>
                        <th>cpf</th>
                        <th>Telefone</th>
                        <th>ações</th>
                    </tr>
                </thead>

                <body>
                    <?php
                    function mascararCPF($cpf)
                    {
                        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
                        if (strlen($cpfLimpo) != 11) return "***.***.***-**";
                        return "***.***." . substr($cpfLimpo, 6, 3) . "-" . substr($cpfLimpo, 9, 2);
                    }

                    function mascararEmail($email)
                    {
                        $partes = explode('@', trim($email));
                        if (count($partes) != 2) return $email;
                        $usuario = $partes[0];
                        $tamanho = strlen($usuario);
                        if ($tamanho <= 2) {
                            $usuarioMascarado = substr($usuario, 0, 1) . '***';
                        } else {
                            $usuarioMascarado = substr($usuario, 0, 2) . str_repeat('*', $tamanho - 2);
                        }
                        return $usuarioMascarado . '@' . $partes[1];
                    }

                    function mascararTelefone($telefone)
                    {
                        $telLimpo = preg_replace('/[^0-9]/', '', $telefone);
                        if (strlen($telLimpo) == 11) return '(' . substr($telLimpo, 0, 2) . ') 9****-' . substr($telLimpo, 7, 4);
                        if (strlen($telLimpo) == 10) return '(' . substr($telLimpo, 0, 2) . ') ****-' . substr($telLimpo, 6, 4);
                        return "***********";
                    }


                    // Chama o Controlador
                    require_once __DIR__ . '/../../../../app/Controllers/BarbeiroController.php';
                    $controller = new BarbeiroController();

                    // Recebe a lista de barbeiros pronta
                    $res = $controller->listar();

                    if (count($res) > 0) {


                        for ($i = 0; $i < count($res); $i++) { //cria a linha na tabela de bd 
                            $id = $res[$i]['id'];
                            $nome = $res[$i]['nome'];
                            $email = $res[$i]['email'];
                            $cpf = $res[$i]['cpf'];
                            $telefone = $res[$i]['telefone'];

                            //aqui vão ser os email reais apenas para usar no editar
                            $email_real = $res[$i]['email'];
                            $cpf_real = $res[$i]['cpf'];
                            $telefone_real = $res[$i]['telefone'];

                            //dados mascarados
                            $email_tela = mascararEmail($res[$i]['email']);
                            $cpf_tela = mascararCPF($res[$i]['cpf']);
                            $telefone_tela = mascararTelefone($res[$i]['telefone']);

                            echo "<tr>";
                            echo "<td>{$nome}</td>";
                            echo "<td>{$email_tela}</td>";    // <-- Mudou aqui
                            echo "<td>{$cpf_tela}</td>";      // <-- Mudou aqui
                            echo "<td>{$telefone_tela}</td>"; // <-- Mudou aqui
                            echo "<td>";
                            echo "<a href='#' class='btn btn-warning btn-sm text-white' title='Editar' onclick='editar({$id}, \"{$nome}\", \"{$email}\", \"{$cpf}\", \"{$telefone}\")'>Editar</a>";
                            echo "<a href='../../public/index.php?acao=visualizar_dados_barbeiro&id={$id}' class='btn btn-info btn-sm mx-1 text-white' title='Ver Dados Sensíveis'>Ver dados</a>";
                            echo "<a href='../../public/index.php?acao=excluir_barbeiro&id={$id}' class='btn btn-danger btn-sm text-white ml-2' title='Excluir' onclick=\"return confirm('Atenção: Deseja excluir o barbeiro {$nome}?');\">Excluir</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else { //se não tiver nenhum barbeiro

                        echo '<tr><td colspan="5" class="text-center">Nenhum barbeiro cadastrado ainda.</td></tr>';
                    }
                    ?>
                    <tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalCadastrarBarbeiro" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="box-shadow: none; border: 1px solid #ccc;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true"></span>
                    </button>
                    <h4 class="modal-title">Cadastrar novo Barbeiro</h4>
                </div>
                <form action="../../public/index.php?acao=cadastrar_barbeiro" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>nome do Barbeiro</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>cpf</label>
                            <input type="text" name="cpf" class="form-control" maxlength="14" required>
                        </div>

                        <div class="form-group">
                            <label>telefone</label>
                            <input type="text" name="telefone" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Senha</label>
                            <input type="password" name="senha" id="senha" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Confirmar Senha</label>
                            <input type="password" name="confirma_senha" id="confirma_senha" class="form-control" required>
                        </div>

                        <div class="form-check mb-3 mt-2">
                            <input type="checkbox" class="form-check-input" name="termo_lgpd" id="termo_lgpd" required>
                            <label class="form-check-label text-muted" for="termo_lgpd" style="font-size: 0.9em; cursor: pointer;">
                                Concordo com o armazenamento e tratamento dos meus dados pessoais por esta barbearia, em conformidade com a LGPD.
                            </label>
                        </div>

                        <div textalign="center" id="mensagem-cadastro" class=""></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">cancelar</button>
                        <button type="submit" class="btn btn-success">salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarBarbeiro" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="box-shadow: none; border: 1px solid #ccc;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true"></span>
                    </button>
                    <h4 class="modal-title">Editar Barbeiro</h4>
                </div>
                <form action="../../public/index.php?acao=editar_barbeiro" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="id_editar" name="id">

                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" id="nome_editar" name="nome" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="email_editar" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>cpf</label>
                            <input type="text" id="cpf_editar" name="cpf" class="form-control" maxlength="14" required>
                        </div>

                        <div class="form-group">
                            <label>Celular</label>
                            <input type="text" id="telefone_editar" name="telefone" class="form-control">
                        </div>

                        <div textalign="center" id="mensagem-editar" class=""></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">cancelar</button>
                        <button type="submit" class="btn btn-primary">atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script type="text/javascript">
        $("#form-barbeiro").submit(function(event) {
            event.preventDefault();
            var senha = $('#senha').val();
            var confirma_senha = $('#confirma_senha').val();


            if (senha !== confirma_senha) {
                $('#mensagem-cadastro').removeClass('text-success').addClass('text-danger');
                $('#mensagem-cadastro').text('Erro: As senhas não coincidem!');
                return;
            }


            var formData = new FormData(this);

            $.ajax({ //ajax para cadastro
                url: "paginas_adm/barbeiros/cadastro_barbeiro.php",
                type: 'POST',
                data: formData,
                success: function(mensagem) {
                    $('#mensagem-cadastro').text('');
                    $('#mensagem-cadastro').removeClass('text-success text-danger');

                    if (mensagem.trim() == "Salvo com Sucesso") {
                        $('#mensagem-cadastro').addClass('text-success');
                        $('#mensagem-cadastro').text(mensagem);
                        $('#form-barbeiro')[0].reset(); // Limpa os campos
                    } else {
                        $('#mensagem-cadastro').addClass('text-danger');
                        $('#mensagem-cadastro').text(mensagem);
                    }
                },


                cache: false,
                contentType: false,

                processData: false,
            });

        });
    </script>

    <script type="text/javascript">
        function editar(id, nome, email, cpf, telefone) {

            $('#id_editar').val(id);
            $('#nome_editar').val(nome);
            $('#email_editar').val(email);
            $('#cpf_editar').val(cpf);
            $('#telefone_editar').val(telefone);
            $('#modalEditarBarbeiro').modal('show');
        }


        $("#form-editar-barbeiro").submit(function(event) { //AJAX q salva a edicao do barbeiro
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "paginas_adm/barbeiros/editarbarbeiro.php",
                type: 'POST',
                data: formData,

                success: function(mensagem) {
                    $('#mensagem-editar').text('');
                    $('#mensagem-editar').removeClass('text-success text-danger');

                    if (mensagem.trim() == "Editado com Sucesso") {
                        location.reload(); // recarrega a página para atualizar a tabela
                    } else {
                        $('#mensagem-editar').addClass('text-danger');
                        $('#mensagem-editar').text(mensagem);
                    }
                },


                cache: false,
                contentType: false,
                processData: false,
            });
        });


        function excluir(id) {
            if (confirm("Tem certeza que deseja excluir este barbeiro? Esta ação não pode ser desfeita.")) {

                //AJAX para Excluir
                $.ajax({
                    url: "paginas_adm/barbeiros/excluir_barbeiro.php",
                    type: 'POST',
                    data: {
                        id: id
                    },


                    success: function(mensagem) {
                        if (mensagem.trim() == "excluído") {
                            location.reload(); //recarrega a pagina
                        } else {
                            alert(mensagem);
                        }
                    }


                });
            }
        }
    </script>