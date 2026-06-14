<?php
@session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Acesso negado'); window.location='index.php';</script>";
    exit();
}


if (!isset($_SESSION['id']) || $_SESSION['id'] == "") {
    echo "<script>window.location='sistema/index.php'</script>";
    exit();
}


// Conecta ao banco de dados logo que a página abre
require_once 'config/database.php';
// o resto do código (buscando serviços e barbeiros) continua igual
require_once 'config/database.php';
require_once 'app/Models/Agendamento.php'; //reaproveita a modal
require_once 'sistema/painel/API_telegram.php'; //pega a  API


$database = new Database(); //conecta com o bd
$db = $database->getConnection(); //conecta com o bd
$agendamento = new Agendamento($db); //inicia a classe

//recebe os dados do formulário do site
$cliente = $_POST['nome_cliente'];
$funcionario = $_POST['funcionario'];
$servicos = $_POST['servico'];
$data = $_POST['data'];
$hora = $_POST['hora'];
$telefone = $_POST['telefone'];
$observacao = "Tel do Cliente: " . $telefone . " Agendado pelo Site";



$queryServico = "SELECT tempo FROM servicos WHERE id = :id_servico"; // busca a duração do serviço
$Servico = $db->prepare($queryServico);
$Servico->bindParam(':id_servico', $servicos);
$Servico->execute();
$dadosServico = $Servico->fetch(PDO::FETCH_ASSOC);

$duracaoMinutos = $dadosServico ? $dadosServico['tempo'] : 30; // 30 min por padrão se não achar

//Calcula a hora do fim com base no tempo real
$hora_fim = date('H:i:s', strtotime("+$duracaoMinutos minutes", strtotime($hora)));

if ($agendamento->verificarHorarioOcupado($funcionario, $data, $hora, $hora_fim, $duracaoMinutos)) {
    echo "<script>alert('Horário indisponível, este barbeiro já tem um cliente marcado neste período.'); window.history.back();</script>";
    exit();
}
$resultado = $agendamento->cadastrarAgendamento($cliente, $funcionario, $servicos, $data, $hora, $observacao); //cadastra no bd

if ($resultado) { //func do telegram
    $data_formatada = date('d/m/Y', strtotime($data));
    $msgTelegram = " NOVO AGENDAMENTO PELO SITE!\n\nCliente: $cliente\nData: $data_formatada\nHora: $hora\nObservação: $observacao";
    enviarMensagemTelegram($msgTelegram); // Dispara a notificação para o numero


    echo "<script>alert('Agendamento realizado com sucesso!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('não foi póssivel realizar o agendamento'); window.history.back();</script>";
}
