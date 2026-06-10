<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Usuario.php';

class UsuarioController
{

    public function cadastrar()
    {
        if (!isset($_POST['aceite_lgpd'])) {
            echo "<script>window.alert(' É preciso aceitar e ter concetimento dos termos da LGPD descritos para realizar seu cadastro); window.history.back();</script>";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome = $_POST['nome'];
            $cpf = $_POST['cpf'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $confirma_senha = $_POST['confirma_senha'];

            // Ve se digitou as duas senhas iguais
            if ($senha !== $confirma_senha) {
                echo "<script>window.alert('As senhas digitadas não coincidem!'); window.history.back();</script>";
                return;
            }

            $database = new Database();
            $db = $database->getConnection();
            $usuario = new Usuario($db);
            $token = bin2hex(random_bytes(16));


            if ($usuario->cadastrarCliente($nome, $cpf, $email, $senha, $token)) {

                require_once __DIR__ . '/../../sistema/EmailService.php';
                $emailService = new EmailService();

                // 2. Montamos o link mágico
                //subistituir pelo nome da hospedagem dps 
                $link = "http://localhost/barbearia/sistema/verifica_email.php?token=" . $token;


                $emailService->enviarValidacaoEmail($email, $nome, $link);

                echo "<script>window.alert('Cadastro realizado! Verifique seu e-mail para ativar a conta.'); window.location='../sistema/index.php';</script>";
            } else {
                echo "<script>window.alert('Erro ao cadastrar!'); window.history.back();</script>";
            }
        }
    }
    public function editar()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) { //verifica se o formulario enviado possui o id de tem mandou 

            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $cpf = $_POST['cpf'];
            $ativo = $_POST['ativo'];

            $database = new Database();
            $db = $database->getConnection();
            $usuarioDB = new Usuario($db);


            if ($usuarioDB->editarUsuario($id, $nome, $email, $cpf, $ativo)) {
                echo "<script>window.alert('Editado com sucesso!'); window.location='../sistema/painel/index.php?pag=usuarios';</script>";
            } else {
                echo "<script>window.alert('Erro ao editar!'); window.location='../sistema/painel/index.php?pag=usuarios';</script>";
            }
        }
    }

    public function excluir()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $database = new Database();
            $db = $database->getConnection();
            $usuarioDB = new Usuario($db);


            if ($usuarioDB->excluirUsuario($id)) {
                echo "<script>window.alert('Excluído com sucesso!'); window.location='../sistema/painel/index.php?pag=usuarios';</script>";
            } else {
                echo "<script>window.alert('Erro ao excluir!'); window.location='../sistema/painel/index.php?pag=usuarios';</script>";
            }
        } else {
            echo "<script>window.location='../sistema/painel/index.php?pag=usuarios';</script>";
        }
    }









    public function listar()
    {

        $database = new Database();
        $db = $database->getConnection();
        $usuarioDB = new Usuario($db);

        return $usuarioDB->listarClientes();
    }









    public function solicitarRecuperacao()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);

            $database = new Database();
            $db = $database->getConnection();
            $usuarioSL = new Usuario($db);

            $usuario = $usuarioSL->verificarEmailExiste($email);

            if ($usuario) {
                $token = bin2hex(random_bytes(32));

                if ($usuarioSL->salvarTokenRecuperacao($email, $token)) {
                    require_once __DIR__ . '/../../sistema/EmailService.php';
                    $emailService = new EmailService();

                    // O link vai apontar para a sua tela de redefinir senha
                    $link = "http://localhost/barbearia/sistema/redefinir_senha.php?token=" . $token;
                    $emailService->enviarRecuperacaoSenha($email, $usuario['nome'], $link);
                }
            }
            echo "<script>window.alert('você ira receber um link de recuperação em instantes'); window.location='../sistema/index.php';</script>";
        }
    }

    public function redefinirSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $confirma = $_POST['confirma'] ?? '';

            if (empty($token) || empty($senha)) {
                echo "<script>window.alert('Dados incompletos'); window.history.back();</script>";
                return;
            }
            if ($senha !== $confirma) {
                echo "<script>window.alert('as senhas não são iguais'); window.history.back();</script>";
                return;
            }
            if (strlen($senha) < 8) {
                echo "<script>window.alert('a senha deve ter no mínimo 8 caracteres.'); window.history.back();</script>";
                return;
            }

            $database = new Database();
            $db = $database->getConnection();
            $usuarioRS = new Usuario($db);

            $registro = $usuarioRS->validarTokenRecuperacao($token);

            if ($registro) {
                $email = $registro['email'];
                if ($usuarioRS->atualizarSenhaRecuperacao($email, $senha, $token)) {
                    echo "<script>window.alert('Senha redefinida com sucesso!'); window.location='../sistema/index.php';</script>";
                } else {
                    echo "<script>window.alert('Erro ao atualizar a senha.'); window.history.back();</script>";
                }
            } else {
                echo "<script>window.alert('Link inválido'); window.location='../sistema/index.php';</script>";
            }
        }
    }
}
