<?php
$token = "Substituido por segurança";
$chat_id = "ID_Substituido por segurança";

$msg = "Conexao com sucesso !!!!!!!!!!!!!!!!";


$mensagem_url = urlencode($msg);
$url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&text={$mensagem_url}";
$resposta = @file_get_contents($url);


if ($resposta) {
    echo "<h1>sucesso</h1>";
} else {
    echo "<h1>Erro</h1>";
}
