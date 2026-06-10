<?php
require_once __DIR__ . '/../Models/Servico.php';
require_once __DIR__ . '/../../config/database.php';

class ServicoController
{
    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Captura os dados do formulário
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $descricao = $_POST['descricao'];
            $valor = $_POST['valor'];
            $tempo = $_POST['tempo'];

            // Conecta ao banco
            $database = new Database();
            $db = $database->getConnection();
            $servico = new Servico($db);

            // Verifica se é um cadastro novo (ID vazio) ou edição (ID preenchido)
            if (empty($id)) {
                $resultado = $servico->cadastrar($descricao, $valor, $tempo);
            } else {
                $resultado = $servico->editar($id, $descricao, $valor, $tempo);
            }
            if ($resultado) {
                echo "Salvo com Sucesso!";
            } else {
                echo "Erro ao salvar!";
            }
        }
    }

    public function excluir()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            $database = new Database();
            $db = $database->getConnection();
            $servico = new Servico($db);

            $resultado = $servico->excluir($id);

            if ($resultado) {
                echo "Excluído com Sucesso!";
            } else {
                echo "Erro ao excluir!";
            }
        }
    }

    public function listar()
    {
        $database = new Database();
        $db = $database->getConnection();
        $servico = new Servico($db);

        return $servico->listar();
    }
}
