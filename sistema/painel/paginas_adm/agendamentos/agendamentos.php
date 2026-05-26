<?php
$id_usuario = $_SESSION['id'];
?>

<div class="row">
  <div class="col-md-12">
    <button onclick="inserir()" type="button" class="btn btn-primary btn-flat btn-pri" data-toggle="modal" data-target="#modalForm">
      <i class="fa fa-plus" aria-hidden="true"></i> Novo Agendamento
    </button>
  </div>
</div>

<div class="bs-example widget-shadow" style="padding:15px; margin-top:15px;">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>data</th>

        <th>hora</th>
        <th>serviço</th>
        <th>cliente</th>
        <th>barbeiro</th>
        <th>Obs</th>
        <th>ações</th>
      </tr>
    </thead>

    <body>
      <?php

      $query = $pdo->query("
          SELECT 
              a.id, 
              a.data, 
              a.hora, 
              a.observacao, 
              a.cliente AS id_cliente,
              a.funcionario AS id_funcionario, 
              a.servicos AS id_servico,
              (SELECT nome FROM usuarios WHERE id = a.cliente) AS nome_cliente,
              (SELECT nome FROM usuarios WHERE id = a.funcionario) AS nome_funcionario,
              (SELECT descricao FROM servicos WHERE id = a.servicos) AS nome_servico
          FROM agendamentos a
          ORDER BY a.data DESC, a.hora DESC
      ");
      // ESSE CODIGO DE CIME ELE FAZ COM QUE TRAGA OQUE FOI SALVO NO BANCO DE DADOS 



      $res = $query->fetchAll(PDO::FETCH_ASSOC);

      for ($i = 0; $i < count($res); $i++) {
        $id = $res[$i]['id'];
        $data_banco = $res[$i]['data'];
        $hora_banco = $res[$i]['hora'];

        $data = implode('/', array_reverse(explode('-', $data_banco))); //explode separa a data, array reverse inverte a ordem pois é salvo no formato EUA, implode junta tudo
        $hora = date('H:i', strtotime($hora_banco)); // H:I deixa na hora, minuto. strtotime faz com que o BD utilize o relogio dele proprio para fazer o calculo


        $cliente = $res[$i]['nome_cliente'];
        $funcionario = $res[$i]['nome_funcionario'];
        $servico = $res[$i]['nome_servico'] ? $res[$i]['nome_servico'] : 'Não informado'; //nome de cada tabela do bd
        $observacao = $res[$i]['observacao'];


        $id_cli = $res[$i]['id_cliente'];
        $id_func = $res[$i]['id_funcionario']; //id de cada tabela do BD
        $id_svc = $res[$i]['id_servico'];
      ?>
        <tr>
          <td><?php echo $data; ?></td>
          <td><?php echo $hora; ?></td>
          <td><strong><?php echo $servico; ?></strong></td>
          <td><?php echo $cliente; ?></td>
          <td><?php echo $funcionario; ?></td>
          <td><?php echo $observacao; ?></td>
          <td>
            <a href="#" onclick="editar('<?php echo $id; ?>', '<?php echo $data_banco; ?>', '<?php echo $hora_banco; ?>', '<?php echo $id_cli; ?>', '<?php echo $id_func; ?>', '<?php echo $observacao; ?>', '<?php echo $id_svc; ?>')" title="Editar" data-toggle="modal" data-target="#modalForm" class="btn btn-warning btn-sm">
              <i class="fa fa-edit"></i>
            </a>
            <a href="paginas_adm/agendamentos/agendamentos_excluir.php?id=<?php echo $id; ?>" title="Excluir" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir este agendamento?');">
              <i class="fa fa-trash"></i>
            </a>
          </td>
        </tr>
      <?php } ?>

    </body>

  </table>

</div>


<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
        <h4 class="modal-title" id="tituloModal">Novo Agendamento</h4>
      </div>

      <form action="paginas_adm/agendamentos/agendamentos_salvar.php" method="POST">
        <div class="modal-body">

          <input type="hidden" name="id" id="id_agendamento">
          <input type="hidden" name="servicos" id="input_servico">
          <input type="hidden" name="funcionario" id="input_funcionario">

          <div id="etapa-servicos">
            <h4>selecione seu serviço </h4>
            <br>

            <?php
            $query_svc = $pdo->query("SELECT * FROM servicos ORDER BY descricao ASC");
            $res_svc = $query_svc->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($res_svc); $i++) {
              $id_svc = $res_svc[$i]['id'];
              $descricao_svc = $res_svc[$i]['descricao'];
              $valor_svc = number_format($res_svc[$i]['valor'], 2, ',', '.');
            ?>

              <button type="button" class="btn btn-default btn-block text-left" style="margin-bottom: 10px; font-size: 16px;" onclick="selecionarServico('<?php echo $id_svc; ?>')">
                <?php echo $descricao_svc; ?> - <b>R$ <?php echo $valor_svc; ?></b>
              </button>
            <?php } ?>
          </div>

          <div id="etapa-barbeiros" style="display: none;">
            <button type="button" class="btn btn-warning btn-sm" onclick="voltarEtapa('servicos')">voltar</button>
            <h4 style="margin-top: 15px;">escolha o profissional</h4>
            <br>

            <?php
            $query_barb = $pdo->query("SELECT * FROM usuarios WHERE nivel = 'barbeiro' ORDER BY nome ASC");
            $res_barb = $query_barb->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($res_barb); $i++) {
              $id_barb = $res_barb[$i]['id'];
              $nome_barb = $res_barb[$i]['nome'];
            ?>
              <button type="button" class="btn btn-default btn-block text-left" style="margin-bottom: 10px; font-size: 16px;" onclick="selecionarBarbeiro('<?php echo $id_barb; ?>')">
                <i class="fa fa-user"></i> <?php echo $nome_barb; ?>
              </button>
            <?php } ?>
          </div>

          <div id="etapa-resumo" style="display: none;">
            <button type="button" class="btn btn-warning btn-sm" onclick="voltarEtapa('barbeiros')">voltar</button>
            <h4 style="margin-top: 15px;"> Data e Cliente</h4>
            <br>

            <div class="row">
              <div class="col-md-6 form-group">
                <label>data</label>

                <input type="date" class="form-control" name="data" id="data" required>
              </div>

              <div class="col-md-6 form-group">
                <label>horario</label>
                <input type="time" class="form-control" name="hora" id="hora" required>
              </div>

            </div>

            <div class="form-group">
              <label>cliente</label>
              <select class="form-control" name="cliente" id="cliente" required>

                <option value="">selecione o Cliente</option>
                <?php
                $query_cli = $pdo->query("SELECT * FROM usuarios WHERE nivel = 'cliente' AND ativo = 'sim' ORDER BY nome ASC");
                $res_cli = $query_cli->fetchAll(PDO::FETCH_ASSOC);
                for ($i = 0; $i < count($res_cli); $i++) {
                  echo "<option value='" . $res_cli[$i]['id'] . "'>" . $res_cli[$i]['nome'] . "</option>";
                }
                ?>

              </select>
            </div>

            <div class="form-group">
              <label>Obs</label>
              <input type="text" class="form-control" name="observacao" id="observacao" maxlength="100">
            </div>
          </div>

        </div>

        <div class="modal-footer" id="rodape-salvar" style="display: none;">
          <button type="submit" class="btn btn-primary">Salvar Agendamento</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  function inserir() {
    $('#tituloModal').text('Novo Agendamento');

    $('#id_agendamento').val('');

    $('#input_servico').val('');
    $('#input_funcionario').val('');
    $('#data').val('');
    $('#hora').val('');
    $('#cliente').val('');
    $('#observacao').val('');
    voltarEtapa('servicos');
  }

  function selecionarServico(idServico) {
    $('#input_servico').val(idServico);
    $('#etapa-servicos').hide();
    $('#etapa-barbeiros').fadeIn();
  }



  function selecionarBarbeiro(idBarbeiro) {
    $('#input_funcionario').val(idBarbeiro);
    $('#etapa-barbeiros').hide();
    $('#etapa-resumo').fadeIn();
    $('#rodape-salvar').show();
  }

  function voltarEtapa(etapaDestino) {
    if (etapaDestino === 'servicos') {
      $('#etapa-barbeiros').hide();
      $('#etapa-resumo').hide();
      $('#rodape-salvar').hide();
      $('#etapa-servicos').fadeIn();

    } else if (etapaDestino === 'barbeiros') {

      $('#etapa-resumo').hide();
      $('#rodape-salvar').hide();
      $('#etapa-barbeiros').fadeIn();

    }
  }


  function editar(id, data, hora, cliente, funcionario, observacao, servico) {

    $('#tituloModal').text('Editar Agendamento');
    $('#id_agendamento').val(id);
    $('#data').val(data);
    $('#hora').val(hora);
    $('#cliente').val(cliente);
    $('#input_funcionario').val(funcionario);
    $('#observacao').val(observacao);
    $('#input_servico').val(servico);
    $('#etapa-servicos').hide();
    $('#etapa-barbeiros').hide();
    $('#etapa-resumo').show();
    $('#rodape-salvar').show();

  }
</script>