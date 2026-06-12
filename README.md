Sistema de Gestão e Agendamento para Barbearia

Funcionalidades:
O sistema conta com regras de negócio completas para o gerenciamento do salão:
* Agendamento Online: Clientes podem escolher serviços, barbeiros e horários disponíveis.
* Painel Administrativo: Gestão de clientes, barbeiros, serviços e controle de horários.
* Notificações via Telegram: Integração com bot do Telegram para alertas de novos agendamentos e interações.
* Recuperação e Alertas por E-mail: Envio de e-mails via SMTP para recuperação de senhas e comunicados.
* Autenticação Segura: Senhas criptografadas no banco de dados e proteção de rotas.
* Usuário Mestre: Script de semeadura (seeder) para criação automática do administrador principal.

Arquitetura e Tecnologias:

O projeto foi construído utilizando as seguintes tecnologias:
* Linguagem: PHP 
* Banco de Dados: MySQL com PDO
* Gerenciador de Dependências: Composer
* Frontend: HTML5, CSS3, JavaScript
* Segurança: Variáveis de ambiente (`.env`) gerenciadas pelo pacote `vlucas/phpdotenv`
* Integrações de API: API do Telegram e serviço SMTP (PHPMailer/nativo)

Como Executar o Projeto Localmente: 

1.Para rodar este projeto na sua máquina, você precisará ter o [XAMPP](https://www.apachefriends.org/pt_br/index.html) e o [Composer](https://getcomposer.org/) instalados.

2.Mova para o servidor: Coloque a pasta do projeto dentro do diretório htdocs (se estiver usando XAMPP).

3.Configuração do Banco de Dados:

4.Abra o phpMyAdmin (http://localhost/phpmyadmin).

5.Crie um banco de dados com o nome desejado.
Configuração da Conexão:
Edite o arquivo config/database.php.
Altere as variáveis conforme suas configurações locais:

