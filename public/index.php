<?php
session_start();
$acao = isset($_GET['acao']) ? $_GET['acao'] : 'login';
require_once __DIR__ . '/../vendor/autoload.php'; // Carrega o autoloader do Composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); //le o arquivo .env para tirar as credenciais do código para variáveis de ambiente
$dotenv->load();

switch ($acao) {


    //sessão para login/cadastro
    case 'cadastrar_cliente': //cadastro
        require_once __DIR__ . '/../app/Controllers/UsuarioController.php';
        $usuario = new UsuarioController();
        $usuario->cadastrar();
        break;

    case 'login':
        require_once __DIR__ . '/../sistema/index.php';
        break;

    case 'logar':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $auth = new AuthController();
        $auth->login();
        break;

    case 'editar':
        require_once __DIR__ . '/../app/Controllers/UsuarioController.php';
        $usuario = new UsuarioController();
        $usuario->editar();
        break;

    case 'excluir':
        require_once __DIR__ . '/../app/Controllers/UsuarioController.php';
        $usuario = new UsuarioController();
        $usuario->excluir();
        break;





    //sessão do barbeiro 
    case 'cadastrar_barbeiro':
        require_once __DIR__ . '/../app/Controllers/BarbeirosController.php';
        $barbeiro = new BarbeiroController();
        $barbeiro->cadastrar();
        break;

    case 'excluir_barbeiro':
        require_once __DIR__ . '/../app/Controllers/BarbeirosController.php';
        $barbeiro = new BarbeiroController();
        $barbeiro->excluir();
        break;

    case 'editar_barbeiro':
        require_once __DIR__ . '/../app/Controllers/BarbeirosController.php';
        $barbeiro = new BarbeiroController();
        $barbeiro->editar();
        break;




    //sessão de agendamento 
    case 'salvar_agendamento':
        require_once __DIR__ . '/../app/Controllers/AgendamentoController.php';
        $agendamento = new AgendamentoController();
        $agendamento->salvar();
        break;

    case 'excluir_agendamento':
        require_once __DIR__ . '/../app/Controllers/AgendamentoController.php';
        $agendamento = new AgendamentoController();
        $agendamento->excluir();
        break;




    //sessão para emailservice 
    case 'solicitar_recuperacao':
        require_once __DIR__ . '/../app/Controllers/UsuarioController.php';
        $usuario = new UsuarioController();
        $usuario->solicitarRecuperacao();
        break;

    case 'redefinir_senha':
        require_once __DIR__ . '/../app/Controllers/UsuarioController.php';
        $usuario = new UsuarioController();
        $usuario->redefinirSenha();
        break;


    //Pagamento
    case 'gerar_pagamento':
        require_once __DIR__ . '/../app/Controllers/PagamentoController.php';
        $pagamento = new PagamentoController();
        $pagamento->gerarLinkPagamento();
        break;








    default:
        echo "<h1>página não encontrada</h1>";
        break;
}
