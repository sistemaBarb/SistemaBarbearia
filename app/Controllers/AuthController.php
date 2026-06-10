<?php //sessão aonde verifica e valida o login 
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Usuario.php';

class AuthController
{

    public function login()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') { //verifica se o formulario foi enviado
            $login = $_POST['login'];
            $senha = $_POST['senha'];


            $database = new Database();
            $db = $database->getConnection(); //conecta ao Bnaco de dados

            $usuarioDB = new Usuario($db);

            $dadosUsu = $usuarioDB->buscarPorEmailOuCpf($login); //basca o email ou cpf


            if ($dadosUsu) {

                if ($dadosUsu['ativo'] == 'Não') {
                    echo "<script>window.alert('usuário se encontra inativo, procure o Administrador.'); window.location='../sistema/index.php';</script>";
                    exit;
                }


                if (password_verify($senha, $dadosUsu['senha_cr'])) {

                    if ($dadosUsu['email_verificado'] == 0) {
                        echo "<script>window.alert(' verifique seu e-mail antes de fazer login'); window.location='../sistema/index.php';</script>";
                        exit;
                    }

                    @session_start();
                    $_SESSION['id'] = $dadosUsu['id'];
                    $_SESSION['nome'] = $dadosUsu['nome'];
                    $_SESSION['nivel'] = $dadosUsu['nivel'];
                    $_SESSION['cpf'] = $dadosUsu['cpf'];
                    $_SESSION['foto'] = 'sem-foto.jpg';

                    // Verifica qual é o nível do utilizador que acabou de fazer login
                    if ($_SESSION['nivel'] == 'cliente') {
                        echo "<script>window.location='../index.php';</script>"; //vai para o agendamento 
                        exit();
                    } else {
                        echo "<script>window.location='../sistema/painel/index.php';</script>"; //dashboard
                        exit();
                    }
                } else {
                    echo "<script>window.alert('Senha Incorreta'); window.location='../sistema/index.php';</script>";
                }
            } else {
                echo "<script>window.alert('Usuário não cadastrado'); window.location='../sistema/index.php';</script>";
            }
        }
    }
}
