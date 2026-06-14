<?php
if (@$_SESSION['nivel'] != 'administrador') {
    echo "<script>window.location='index.php'</script>";
    exit();
}
?>

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark">Lista de Clientes</h4>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th class="text-center">Situação</th>
                            <th class="text-center">Ações</th>
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
                            // Limpa tudo que não for número
                            $telLimpo = preg_replace('/[^0-9]/', '', $telefone);
                            $tamanho = strlen($telLimpo);

                            // 1. Se o campo estiver vazio no banco de dados
                            if ($tamanho == 0) {
                                return "<span class='text-muted'>Não informado</span>";
                            }
                            $ultimosQuatroDigitos = substr($telLimpo, -4);
                            if ($tamanho == 11) {
                                // Formato Celular (11 dígitos): (11) 9****-6768
                                return '(' . substr($telLimpo, 0, 2) . ') ' . substr($telLimpo, 2, 1) . '****-' .  $ultimosQuatroDigitos;
                            } elseif ($tamanho > 4) {
                                // Caso acontecça dele passar sem o DDD 
                                return str_repeat('*', $tamanho - 4) . '-' . $ultimosQuatroDigitos;
                            } else {

                                return $telefone;
                            }
                        }

                        require_once __DIR__ . '/../../../../app/Controllers/UsuarioController.php';
                        $controller = new UsuarioController();
                        $res = $controller->listar();
                        if (count($res) > 0) {
                            foreach ($res as $row) {
                                $nome = $row['nome'];
                                $email_real    = $row['email'];
                                $cpf_real      = $row['cpf'];
                                $telefone_real = $row['telefone']; // Puxa o telefone real
                                $ativo         = $row['ativo'];


                                $email_tela    = mascararEmail($row['email']);
                                $cpf_tela      = mascararCPF($row['cpf']);
                                $telefone_tela = mascararTelefone($row['telefone']);

                                // Badge do Bootstrap
                                $badge_status = ($ativo == 'sim')
                                    ? "<span class='badge bg-success'>Ativo</span>"
                                    : "<span class='badge bg-danger'>Inativo</span>";

                                echo "
                                  <tr>
                                 <td><strong>{$nome}</strong></td>
                                 <td>{$email_tela}</td>
                                  <td>{$cpf_tela}</td>
                                 <td>{$telefone_tela}</td> <td class='text-center'>{$badge_status}</td>
                                  <td class='text-center'>
                                  <a href='#' class='btn btn-warning btn-sm mx-1 text-white' title='Editar' data-toggle='modal' data-target='#modalEditar' data-id='{$row['id']}' data-nome='{$nome}' data-email='       {$email_real}' data-cpf='{$cpf_real}' data-telefone='{$telefone_real}' data-ativo='{$ativo}' onclick='preencherModal(this)'>Editar</a>

                                  <a href='../../public/index.php?acao=visualizar_dados&id={$row['id']}' class='btn btn-info btn-sm mx-1 text-white' title='ver Dados'> Ver Dados </a>

                                <a href='../../public/index.php?acao=excluir&id={$row['id']}' class='btn btn-danger btn-sm mx-1 text-white' title='Excluir' onclick=\"return confirm('Tem certeza que deseja excluir o cliente {$nome}? Esta ação não pode ser desfeita.');\">Excluir</a>
                                 </td>
                                 </tr>
                                 ";
                            }
                        } else {

                            echo "
                            <tr>
                                <td colspan='5' class='text-center text-muted py-4'>
                                    Nenhum cliente cadastrado no sistema.
                                </td>
                            </tr>
                            ";
                        }
                        ?>
                    </body>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold" id="tituloModal">atualizar dados do Cliente</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true"></span>
                </button>
            </div>

            <form action="../../public/index.php?acao=editar" method="post">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">nome do cliente</label>
                            <input type="text" class="form-control" name="nome" id="edit_nome" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">CPF</label>
                            <input type="text" class="form-control" name="cpf" id="edit_cpf">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">situação</label>
                            <select class="form-select form-control" name="ativo" id="edit_ativo">
                                <option value="sim">Ativo</option>
                                <option value="nao">Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">cancelar</button>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fa fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function preencherModal(botao) {
        // Pega os dados que estão escondidos no botão amarelo e joga para os inputs
        document.getElementById('edit_id').value = botao.getAttribute('data-id');
        document.getElementById('edit_nome').value = botao.getAttribute('data-nome');
        document.getElementById('edit_email').value = botao.getAttribute('data-email');
        document.getElementById('edit_cpf').value = botao.getAttribute('data-cpf');
        document.getElementById('edit_ativo').value = botao.getAttribute('data-ativo');
    }
</script>