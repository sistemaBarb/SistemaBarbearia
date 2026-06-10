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

    public function enviarValidacaoEmail(string $emailDestino, string $nomeDestino, string $linkValidacao): bool
    {
        try {
            $this->mailer->clearAddresses();

            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);
            $this->mailer->addAddress($emailDestino, $nomeDestino);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Ative sua conta - Sistema Barbearia';
            $this->mailer->Body = $this->templateVerificacao($nomeDestino, $linkValidacao);
            $this->mailer->AltBody = "Olá $nomeDestino, acesse este link para ativar sua conta: $linkValidacao";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('[EmailService] Falha ao enviar email de verificacao: ' . $this->mailer->ErrorInfo);
            return false;
        }
    }


    private function templateVerificacao(string $nome, string $link): string
    {
        return "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <title>Verificação de E-mail</title>
        </head>
        <body style='font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif; background-color: #f8f9fa; padding: 20px; margin: 0;'>
            
            <div style='max-width: 600px; margin: 0 auto;'>
                
                <div style='background-color: #ffffff; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 1px 1px rgba(0,0,0,.05);'>
                    
                    <div style='background-color: #f5f5f5; border-bottom: 1px solid #ddd; padding: 10px 15px; border-top-left-radius: 3px; border-top-right-radius: 3px;'>
                        <h3 style='margin: 0; font-size: 16px; color: #333333; font-weight: 500;'>Sistema Barbearia - Confirmação de Cadastro</h3>
                    </div>

                    <div style='padding: 15px;'>
                        <h4 style='margin-top: 0; color: #333;'>Olá, " . htmlspecialchars($nome) . "!</h4>
                        <p style='color: #555; font-size: 14px; line-height: 1.5;'>Falta apenas um passo para você acessar o sistema e começar a agendar seus cortes.</p>
                        <p style='color: #555; font-size: 14px; line-height: 1.5;'>Por favor, clique no botão abaixo para verificar seu endereço de e-mail e ativar a sua conta.</p>
                        
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='" . htmlspecialchars($link) . "' style='display: inline-block; padding: 10px 16px; font-size: 18px; line-height: 1.3333333; border-radius: 6px; color: #ffffff; background-color: #5cb85c; border: 1px solid #4cae4c; text-decoration: none; font-weight: bold;'>
                                Ativar Minha Conta
                            </a>
                        </div>

                        <hr style='border: 0; border-top: 1px solid #eeeeee; margin: 20px 0;'>
                        
                        <p style='color: #777777; font-size: 12px; line-height: 1.5;'>
                            Se o botão acima não funcionar, copie e cole a URL abaixo no seu navegador:<br>
                            <a href='" . htmlspecialchars($link) . "' style='color: #337ab7; word-break: break-all;'>" . htmlspecialchars($link) . "</a>
                        </p>
                        <p style='color: #777777; font-size: 12px; margin-bottom: 0;'>
                            Se você não solicitou este cadastro, pode ignorar este e-mail com segurança.
                        </p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
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
}
