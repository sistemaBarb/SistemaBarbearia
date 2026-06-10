
<?php
function enviarMensagemTelegram($texto_mensagem)
{
    $token = "8777438398:AAFjCN2sA6Rwg_A7COzuVFfC4fMZeUdCq-Y";
    $chat_id = "8968719973";
    $mensagem_url = urlencode($texto_mensagem);
    $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&text={$mensagem_url}";

    @file_get_contents($url);
}
