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
        <th>Data</th>
        <th>Hora</th>
        <th>Cliente (ID)</th>
        <th>Funcionário (ID)</th>
        <th>Observação</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Listando os agendamentos do banco
      $query = $pdo->query("SELECT * FROM agendamentos ORDER BY data DESC, hora DESC");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);

      for ($i = 0; $i < count($res); $i++) {
        $id = $res[$i]['id'];
        // Formatando a data e a hora para o padrão brasileiro visualmente
        $data = implode('/', array_reverse(explode('-', $res[$i]['data'])));
        $hora = date('H:i', strtotime($res[$i]['hora']));
        $cliente = $res[$i]['cliente'];
        $funcionario = $res[$i]['funcionario'];
        $observacao = $res[$i]['observacao'];
      ?>
        <tr>
          <td><?php echo $data; ?></td>
          <td><?php echo $hora; ?></td>
          <td><?php echo $cliente; ?></td>
          <td><?php echo $funcionario; ?></td>
          <td><?php echo $observacao; ?></td>
          <td>
            <a href="#" onclick="editar('<?php echo $id; ?>', '<?php echo $res[$i]['data']; ?>', '<?php echo $res[$i]['hora']; ?>', '<?php echo $cliente; ?>', '<?php echo $funcionario; ?>', '<?php echo $observacao; ?>')" title="Editar" data-toggle="modal" data-target="#modalForm" class="btn btn-warning btn-sm">
              <i class="fa fa-edit"></i>
            </a>
            <a href="paginas_adm/agendamentos/agendamentos_excluir.php?id=<?php echo $id; ?>" title="Excluir" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir este agendamento?');">
              <i class="fa fa-trash"></i>
            </a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="tituloModal">Novo Agendamento</h4>
      </div>

      <form action="paginas_adm/agendamentos/agendamentos_salvar.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="id" id="id_agendamento">

          <div class="row">
            <div class="col-md-6 form-group">
              <label>Data</label>
              <input type="date" class="form-control" name="data" id="data" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Hora</label>
              <input type="time" class="form-control" name="hora" id="hora" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label>ID do Cliente</label>
              <input type="number" class="form-control" name="cliente" id="cliente" required>
            </div>
            <div class="col-md-6 form-group">
              <label>ID do Funcionário</label>
              <input type="number" class="form-control" name="funcionario" id="funcionario" required>
            </div>
          </div>

          <div class="form-group">
            <label>Observação</label>
            <input type="text" class="form-control" name="observacao" id="observacao" maxlength="100">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function inserir() {
    $('#tituloModal').text('Novo Agendamento');
    $('#id_agendamento').val('');
    $('#data').val('');
    $('#hora').val('');
    $('#cliente').val('');
    $('#funcionario').val('');
    $('#observacao').val('');
  }

  function editar(id, data, hora, cliente, funcionario, observacao) {
    $('#tituloModal').text('Editar Agendamento');
    $('#id_agendamento').val(id);
    $('#data').val(data);
    $('#hora').val(hora);
    $('#cliente').val(cliente);
    $('#funcionario').val(funcionario);
    $('#observacao').val(observacao);
  }
</script>