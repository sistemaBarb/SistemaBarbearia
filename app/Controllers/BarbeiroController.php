<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/barbeiros.php';

class BarbeiroController
{

    public function listar()
    {

        $database = new Database();
        $db = $database->getConnection(); //Faz a conexão com BD 

        $barbeiro = new Barbeiro($db);
        return $barbeiro->listarBarbeiros(); // faz o return devolvendo os barbeiros 
    }

    public function cadastrar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $cpf = $_POST['cpf'];
            // Chama a função para validar o CPF do barbeiro
            if (!$this->validaCPF($cpf)) {
                echo "<script>window.alert('O CPF do barbeiro é inválido! Verifique a digitação.'); window.history.back();</script>";
                return;
            }
            $telefone = $_POST['telefone'];
            $senha = $_POST['senha'];
            $confirma_senha = $_POST['confirma_senha'];


            if ($senha !== $confirma_senha) {
                echo "<script>window.alert('As senhas digitadas não coincidem!'); window.history.back();</script>";
                return;
            }

            $database = new Database();
            $db = $database->getConnection();
            $barbeiro = new Barbeiro($db);

            if ($barbeiro->cadastrarBarbeiro($nome, $email, $cpf, $telefone, $senha)) {
                echo "<script>window.alert('Barbeiro cadastrado com sucesso!'); window.location='../sistema/painel/index.php?pag=barbeiros';</script>";
            } else {
                echo "<script>window.alert('Erro ao cadastrar!'); window.location='../sistema/painel/index.php?pag=barbeiros';</script>";
            }
        }
    }

    public function excluir()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $database = new Database();
            $db = $database->getConnection();
            $barbeiro = new Barbeiro($db);

            if ($barbeiro->excluirBarbeiro($id)) {
                echo "<script>window.alert('Barbeiro excluído com sucesso!'); window.location='../sistema/painel/index.php?pag=barbeiros';</script>";
            } else {
                echo "<script>window.alert('Erro ao excluir!'); window.location='../sistema/painel/index.php?pag=barbeiros';</script>";
            }
        } else {
            echo "<script>window.location='../sistema/painel/index.php?pag=barbeiros';</script>";
        }
    }

    public function editar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $cpf = $_POST['cpf'];
            $telefone = $_POST['telefone'];

            $database = new Database();
            $db = $database->getConnection();
            $barbeiro = new Barbeiro($db);

            if ($barbeiro->EditarBarbeiro($id, $nome, $email, $cpf, $telefone)) {

                echo "<script>window.alert('editado com sucesso'); window.location='../sistema/painel/index.php?pag=barbeiros';</script>";
            } else {
                echo "<script>window.alert('erro ao editar!'); window.location='../sistema/painel/index.php?pag=barbeiros';</script>";
            }
        }
    }
    private function validaCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($i = 9; $i < 11; $i++) {
            for ($d = 0, $c = 0; $c < $i; $c++) {
                $d += $cpf[$c] * (($i + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
