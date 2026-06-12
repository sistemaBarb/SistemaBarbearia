<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Agendamento.php';

class AgendamentoController
{
    public function listar()
    {
        $database = new Database();
        $db = $database->getConnection();
        $agendamento = new Agendamento($db);

        return $agendamento->listarAgendamentos();
    }

    public function getServicos()
    {
        $database = new Database();
        $db = $database->getConnection();
        $agendamento = new Agendamento($db);

        return  $agendamento->listarServicos();
    }

    public function getClientesAtivos()
    {
        $database = new Database();
        $db = $database->getConnection();
        $agendamento = new Agendamento($db);

        return  $agendamento->listarClientesAtivos();
    }

    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $_POST['id'];
            $cliente = $_POST['cliente'];

            $funcionario = $_POST['funcionario'];
            $servicos = $_POST['servicos'];
            $data = $_POST['data'];
            $hora = $_POST['hora'];
            $observacao = $_POST['observacao'];

            $database = new Database();
            $db = $database->getConnection();
            $agendamento = new Agendamento($db);

            $queryServico = "SELECT tempo FROM servicos WHERE id = :id_servico"; //Busca a duração tempo do serviço selecionado no banco
            $retServico = $db->prepare($queryServico);
            $retServico->bindParam(':id_servico', $servicos); // $servicos guarda o ID do serviço do POST
            $retServico->execute();
            $dadosServico = $retServico->fetch(PDO::FETCH_ASSOC);

            $duracao = $dadosServico ? $dadosServico['tempo'] : 40; // Se não achar assume que é 40 minutos
            $hora_fim = date('H:i:s', strtotime("+$duracao minutes", strtotime($hora))); //Calcula a hora do fim do novo agendamento com base no tempo real

            if ($agendamento->verificarHorarioOcupado($funcionario, $data, $hora, $hora_fim, $duracao, $id)) { //envia pra modal
                echo "<script>window.alert('Horário indisponível! O serviço escolhido entra em conflito com a agenda do barbeiro.'); window.history.back();</script>";
                return;
            }
        }
    }

    public function excluir()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $database = new Database();
            $db = $database->getConnection();
            $agendamento = new Agendamento($db);

            if ($agendamento->excluirAgendamento($id)) {
                echo "<script>window.alert('Agendamento excluído'); window.location='../sistema/painel/index.php?pag=agendamentos';</script>";
            } else {
                echo "<script>window.alert('Erro ao excluir'); window.location='../sistema/painel/index.php?pag=agendamentos';</script>";
            }
        } else {
            echo "<script>window.location='../sistema/painel/index.php?pag=agendamentos';</script>";
        }
    }
}
