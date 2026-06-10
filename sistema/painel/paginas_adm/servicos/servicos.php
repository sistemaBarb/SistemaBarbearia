<?php
require_once __DIR__ . '/../../../../app/Controllers/ServicoController.php';
$controller = new ServicoController();
$res = $controller->listar(); // Puxa os serviços do banco
?>

<div class="row">
    <div class="col-md-12">
        <button onclick="inserir()" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalForm">
            NOVO SERVIÇO
        </button>
    </div>
</div>

<div class="bs-example widget-shadow" style="padding:15px; margin-top:15px;">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor-(R$)</th>
                <th>Tempo de Serviço</th>
                <th>Ações</th>
            </tr>
        </thead>

        <body>
            <?php
            // Laço de repetição para criar as linhas da tabela
            if (isset($res) && count($res) > 0) {
                for ($i = 0; $i < count($res); $i++) {
                    $id = $res[$i]['id'];
                    $descricao = $res[$i]['descricao'];
                    $valor = $res[$i]['valor'];
                    $tempo = $res[$i]['tempo'];
            ?>
                    <tr>
                        <td><?php echo $descricao; ?></td>
                        <td>R$ <?php echo number_format($valor, 2, ',', '.'); ?></td>
                        <td><?php echo $tempo; ?></td>
                        <td>
                            <a href="#" onclick="editar('<?php echo $id; ?>', '<?php echo $descricao; ?>', '<?php echo $valor; ?>', '<?php echo $tempo; ?>')" title="Editar Registro" class="btn btn-warning btn-sm text-white">
                                Editar
                            </a>

                            <a href="#" onclick="excluir('<?php echo $id; ?>')" title="Excluir Registro" class="btn btn-danger btn-sm text-white ml-2">
                                Excluir
                            </a>
                        </td>
                    </tr>
            <?php }
            } else {
                echo "<tr><td colspan='4'>Nenhum serviço cadastrado!</td></tr>";
            } ?>

            <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="tituloModal">Novo Serviço</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px" id="btn-fechar">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>

                        <form id="form" method="POST">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <input type="text" class="form-control" id="descricao" name="descricao" required>
                                </div>

                                <div class="form-group">
                                    <label for="valor">valor(R$)</label>
                                    <input type="text" class="form-control" id="valor" name="valor" placeholder="Ex: 35.00" required>
                                </div>

                                <div class="form-group">
                                    <label for="tempo">tempo de serviço</label>
                                    <input type="number" class="form-control" id="tempo" name="tempo" placeholder="Ex: 30" required>
                                </div>

                                <input type="hidden" id="id" name="id">

                                <small>
                                    <div id="mensagem" textalign="center"></div>
                                </small>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="btn-salvar">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                function inserir() {
                    $('#mensagem').text('');
                    $('#tituloModal').text('Novo Serviço');

                    $('#form')[0].reset(); // Limpa os campos

                    $('#id').val('');

                }

                function editar(id, descricao, valor, tempo) {
                    $('#mensagem').text('');

                    $('#tituloModal').text('Editar Serviço');

                    $('#id').val(id);

                    $('#descricao').val(descricao);

                    $('#valor').val(valor);

                    $('#tempo').val(tempo);

                    $('#modalForm').modal('show');
                }

                function excluir(id) {

                    if (confirm('Tem certeza que deseja excluir este serviço?')) {
                        $.ajax({
                            url: "paginas_adm/servicos/servico_excluir.php",
                            method: 'POST',
                            data: {
                                id: id
                            },

                            success: function(mensagem) {
                                if (mensagem.trim() == "Excluído com Sucesso!") {
                                    location.reload();

                                } else {
                                    alert(mensagem);
                                }
                            }
                        });
                    }
                }

                $("#form").submit(function(event) { //manda pro _salvar.php sem recarregar a tela
                    event.preventDefault(); // Impede o formulário de recarregar a página
                    var formData = new FormData(this);

                    $.ajax({
                        url: "paginas_adm/servicos/servico_salvar.php",
                        type: 'POST',
                        data: formData,
                        success: function(mensagem) {
                            $('#mensagem').text('');
                            $('#mensagem').removeClass();

                            if (mensagem.trim() == "Salvo com Sucesso!") {
                                $('#btn-fechar').click();
                                location.reload();
                            } else {
                                $('#mensagem').addClass('text-danger');
                                $('#mensagem').text(mensagem);
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false,
                    });
                });
            </script>

        </body>
    </table>
</div>