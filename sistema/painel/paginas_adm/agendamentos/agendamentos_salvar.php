<?php
@session_start();
require_once('../../../conexao.php');

$id = $_POST['id'];
$data = $_POST['data'];
$hora = $_POST['hora'];
$cliente = $_POST['cliente'];
$funcionario = $_POST['funcionario'];
$servicos = $_POST['servicos'];
$observacao = $_POST['observacao'];
$data_lancamento = date('Y-m-d');
$usuario_lancamento = $_SESSION['id'];



$query_tempo = $pdo->query("SELECT tempo FROM servicos WHERE id = '$servicos'"); // aqui o codigo ve quanto tempo leva o serviço
$res_tempo = $query_tempo->fetchAll(PDO::FETCH_ASSOC);
$minutos_servico = $res_tempo[0]['tempo'];


// Converte os horários do serviço timestamp ou seja o horario do BD
$inicio_novo = strtotime($hora);
$fim_novo = strtotime("+$minutos_servico minutes", $inicio_novo);


$id_verificacao = ($id == "") ? 0 : $id; //faz um ID falso para não confundir com o id já criado e dar conflito que já possue um horário

// o BD  traz a agenda daquele barbeiro no dia escolhido
$query_agenda = $pdo->prepare("
    SELECT a.hora, s.tempo 
    FROM agendamentos a 
    INNER JOIN servicos s ON a.servicos = s.id 
    WHERE a.funcionario = :funcionario 
    AND a.data = :data 
    AND a.id != :id
");

$query_agenda->bindValue(':funcionario', $funcionario);
$query_agenda->bindValue(':data', $data);
$query_agenda->bindValue(':id', $id_verificacao);
$query_agenda->execute();
$agendamentos_do_dia = $query_agenda->fetchAll(PDO::FETCH_ASSOC);

$tem_conflito = false;

//faz um foreach nos agendamentos para ver o horario 
foreach ($agendamentos_do_dia as $agendamento) {

    $inicio_existente = strtotime($agendamento['hora']);
    $fim_existente = strtotime("+" . $agendamento['tempo'] . " minutes", $inicio_existente);


    if ($inicio_novo < $fim_existente && $fim_novo > $inicio_existente) {
        $tem_conflito = true;
        break;
    }
}

//Se houver conflito, exibe o alerta e interrompe o script aqui
if ($tem_conflito) {
    echo "<script>
            alert('Atenção: O profissional já possui um atendimento neste horário!');
            window.location='../../index.php?pag=agendamentos';
          </script>";
    exit();
}

// agora é a parte se não tiver conflito 
if ($id == "") {
    $query = $pdo->prepare("INSERT INTO agendamentos SET funcionario = :funcionario, cliente = :cliente, servicos = :servicos, data = :data, hora = :hora, observacao = :observacao, data_lancamento = :data_lancamento, usuario_lancamento = :usuario_lancamento");
} else {
    $query = $pdo->prepare("UPDATE agendamentos SET funcionario = :funcionario, cliente = :cliente, servicos = :servicos, data = :data, hora = :hora, observacao = :observacao WHERE id = :id");
    $query->bindValue(":id", $id);
}

$query->bindValue(":funcionario", $funcionario);
$query->bindValue(":cliente", $cliente);
$query->bindValue(":servicos", $servicos);
$query->bindValue(":data", $data);
$query->bindValue(":hora", $hora);
$query->bindValue(":observacao", $observacao);

if ($id == "") {
    $query->bindValue(":data_lancamento", $data_lancamento);
    $query->bindValue(":usuario_lancamento", $usuario_lancamento);
}

$query->execute(); //agendamento é salvo no BD



$query_cliente = $pdo->query("SELECT nome FROM usuarios WHERE id = '$cliente'");
$nome_cliente = $query_cliente->fetch(PDO::FETCH_ASSOC)['nome']; //Busca e traz o nome do cliente de acordo com o id de qm ta enviando o formulario 

$query_servico = $pdo->query("SELECT descricao FROM servicos WHERE id = '$servicos'");
$nome_servico = $query_servico->fetch(PDO::FETCH_ASSOC)['descricao']; //mesma coisa q o nome só que com o servico


$data = implode('/', array_reverse(explode('-', $data)));
$hora = date('H:i', strtotime($hora));


$texto_telegram = "Olá $nome_cliente!, seu agendamento de $nome_servico está confirmado para o dia $data às $hora. Até mais !";
dispararTelegram($texto_telegram);


echo "<script>window.location='../../index.php?pag=agendamentos'</script>";
exit();


function dispararTelegram($msg)
{

    $token = "8777438398:AAFjCN2sA6Rwg_A7COzuVFfC4fMZeUdCq-Y";
    $chat_id = "8968719973";

    $mensagem_url = urlencode($msg);
    $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&text={$mensagem_url}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // esse bloco de codigo serve para tirar o bloqueio que o xampp tem para fazer envios para fora do servidor 
    curl_exec($ch);
    curl_close($ch);
}
