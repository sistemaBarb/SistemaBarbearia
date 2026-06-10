<?php

class Agendamento
{
    private $conex;
    private $table_name = "agendamentos";

    public function __construct($db)
    {
        $this->conex = $db;
    }


    public function listarAgendamentos()
    {
        $query = "SELECT 
                    a.id,
                    a.data,
                    a.hora,
                    a.status,
                    a.observacao,
                    a.cliente AS id_cliente,
                    a.funcionario AS id_funcionario,
                    a.servicos AS id_servico,
                    (SELECT nome FROM usuarios WHERE id = a.cliente) AS nome_cliente,
                    (SELECT nome FROM usuarios WHERE id = a.funcionario) AS nome_funcionario,
                    (SELECT descricao FROM servicos WHERE id = a.servicos) AS nome_servico,
                    (SELECT valor FROM servicos WHERE id = a.servicos) AS valor_servico
                  FROM " . $this->table_name . " a 
                  ORDER BY a.data DESC, a.hora DESC";
        // ESSE CODIGO DE CIME ELE FAZ COM QUE TRAGA OQUE FOI SALVO NO BANCO DE DADOS 

        $ret = $this->conex->prepare($query);
        $ret->execute();

        return $ret->fetchAll(PDO::FETCH_ASSOC);
    }


    public function listarServicos()
    {
        $query = "SELECT * FROM servicos ORDER BY descricao ASC";

        $ret = $this->conex->prepare($query);
        $ret->execute();

        return $ret->fetchAll(PDO::FETCH_ASSOC);
    }


    public function listarClientesAtivos()
    {
        $query = "SELECT * FROM usuarios WHERE nivel = 'cliente' AND ativo = 'sim' ORDER BY nome ASC";

        $ret = $this->conex->prepare($query);
        $ret->execute();
        return $ret->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrarAgendamento($cliente, $funcionario, $servicos, $data, $hora, $observacao)
    {

        $query = "INSERT INTO " . $this->table_name . " (cliente, funcionario, servicos, data, hora, observacao, data_lancamento) 
                  VALUES (:cliente, :funcionario, :servicos, :data, :hora, :observacao, CURDATE())"; // CURDATE () salva o momento e a hora do agendamento 

        $ret = $this->conex->prepare($query);

        $ret->bindParam(':cliente', $cliente);
        $ret->bindParam(':funcionario', $funcionario);

        $ret->bindParam(':servicos', $servicos);
        $ret->bindParam(':data', $data);

        $ret->bindParam(':hora', $hora);
        $ret->bindParam(':observacao', $observacao);

        return $ret->execute();
    }

    public function EditarAgendamento($id, $cliente, $funcionario, $servicos, $data, $hora, $observacao)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET cliente = :cliente, funcionario = :funcionario, servicos = :servicos, data = :data, hora = :hora, observacao = :observacao 
                  WHERE id = :id";

        $ret = $this->conex->prepare($query);

        $ret->bindParam(':cliente', $cliente);

        $ret->bindParam(':funcionario', $funcionario);

        $ret->bindParam(':servicos', $servicos);

        $ret->bindParam(':data', $data);

        $ret->bindParam(':hora', $hora);

        $ret->bindParam(':observacao', $observacao);


        return $ret->execute();
    }

    public function verificarHorarioOcupado($funcionario, $data, $hora, $id = null)
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE funcionario = :funcionario AND data = :data AND hora = :hora";

        //vai ignorar o ID, pois já esta editando 
        if (!empty($id)) {
            $query .= " AND id != :id";
        }

        $ret = $this->conex->prepare($query);

        $ret->bindParam(':funcionario', $funcionario);
        $ret->bindParam(':data', $data);
        $ret->bindParam(':hora', $hora);

        if (!empty($id)) {
            $ret->bindParam(':id', $id);
        }

        $ret->execute();

        // Se encontrar alguma linha, retorna está ocupado
        return $ret->rowCount() > 0;
    }

    public function excluirAgendamento($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";


        $ret = $this->conex->prepare($query);
        $ret->bindParam(':id', $id);

        return $ret->execute();
    }
}
