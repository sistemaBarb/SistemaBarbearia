<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private PHPMailer $mailer;
    private array $config;

    public function __construct() //puxa tudo do arquivo de configuração smtp
    {
        $this->config = require __DIR__ . '/smtp_config.php';
        $this->mailer = new PHPMailer(true); // exceções em erros
        $this->configurarSMTP();
    }


    private function configurarSMTP(): void
    {
        // Configurações do servidor SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host       = $this->config['host'];
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $this->config['username'];
        $this->mailer->Password   = $this->config['password'];
        $this->mailer->SMTPSecure = $this->config['encryption']; // manda independente do modo 'tls' ou 'ssl' ( ACHO )
        $this->mailer->Port       = $this->config['port'];
        $this->mailer->CharSet    = 'UTF-8';
    }

    public function enviarRecuperacaoSenha(string $emailDestino, string $nomeDestino, string $linkRedefinir): bool
    {
        try {
            // Limpa destinatários anteriores 
            $this->mailer->clearAddresses();

            //monta o corpo para enviar por email
            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);
            $this->mailer->addAddress($emailDestino, $nomeDestino);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Recuperação de Senha - ' . $this->config['from_name'];
            $this->mailer->Body    = $this->montarTemplate($nomeDestino, $linkRedefinir);
            $this->mailer->AltBody = "Olá $nomeDestino, acesse este link para redefinir sua senha: $linkRedefinir";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('[EmailService] Falha ao enviar: ' . $this->mailer->ErrorInfo);
            return false;
        }
    }





    private function montarTemplate(string $nome, string $link): string
    {
        return "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head><meta charset='UTF-8'></head>
        <body style='font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;'>
            <div style='max-width:600px; margin:0 auto; background:#fff; padding:30px; border-radius:8px;'>
                <h2 style='color:#222;'>Olá, " . htmlspecialchars($nome) . "!</h2>
                <p>Recebemos uma solicitação para redefinir a senha da sua conta na barbearia.</p>
                <p>Clique no botão para criar uma nova senha:</p>
                <p style='text-align:center; margin:30px 0;'>
                    <a href='" . htmlspecialchars($link) . "'
                       style='display:inline-block; padding:14px 28px; background:#1a1a1a; color:#fff;
                              text-decoration:none; border-radius:4px; font-weight:bold;'>
                        Redefinir Minha Senha
                    </a>
                </p>
                <p style='color:#666; font-size:13px;'>Se o botão não funcionar, copie e cole este link no navegador:</p>
                <p style='color:#0066cc; font-size:13px; word-break:break-all;'>" . htmlspecialchars($link) . "</p>
                <hr style='border:none; border-top:1px solid #eee; margin:25px 0;'>
                <p style='color:#999; font-size:12px;'>
                    Este link é válido por <strong>1 hora</strong>.<br>
                    Se você não solicitou esta recuperação, apenas ignore este e-mail.
                </p>
            </div>
        </body>
        </html>";
    }
}
