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


            if ($agendamento->verificarHorarioOcupado($funcionario, $data, $hora, $id)) {
                echo "<script>window.alert('horário indisponível Este barbeiro já tem um cliente marcado nesta data e hora'); window.history.back();</script>";
                return;
            }


            if (empty($id)) {
                $resultado = $agendamento->cadastrarAgendamento($cliente, $funcionario, $servicos, $data, $hora, $observacao);
                $mensagem = 'agendamento salvo com sucesso!';
            } else {
                $resultado = $agendamento->EditarAgendamento($id, $cliente, $funcionario, $servicos, $data, $hora, $observacao);
                $mensagem = 'agendamento editado';
            }

            if ($resultado) {
                require_once __DIR__ . '/../../sistema/painel/API_telegram.php';
                $data_formatada = date('d/m/Y', strtotime($data)); //formata a data para padrão no brasil 
                $msgTelegram = "Seu agendamento está marcado para:\nData: {$data_formatada}\nHora: {$hora}\n\nte esperamos na barbearia!";
                enviarMensagemTelegram($msgTelegram);
                echo "<script>window.alert('$mensagem'); window.location='../sistema/painel/index.php?pag=agendamentos';</script>";
            } else {

                echo "<script>window.alert('Erro ao salvar o agendamento'); window.location='../sistema/painel/index.php?pag=agendamentos';</script>";
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
