
<?php
function enviarMensagemTelegram($texto_mensagem)
{
    // Busca o token e o ID do chat de forma segura
    $token = $_ENV['TELEGRAM_BOT_TOKEN'];
    $chat_id = $_ENV['TELEGRAM_CHAT_ID'];

    $mensagem_url = urlencode($texto_mensagem);
    $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&text={$mensagem_url}";

    @file_get_contents($url);
}
