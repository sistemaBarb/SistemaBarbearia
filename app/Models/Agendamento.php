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

    public function verificarHorarioOcupado($funcionario, $data, $hora_inicio, $hora_fim, $duracaoMinutos, $id = null) // A lógica = A hora final do agendamento existente for maior que a hora inicial do novo, e a hora inicial do agendamento existente for MENOR que a hora final do novo
    {
        $query = "SELECT a.id 
                  FROM " . $this->table_name . " AS a
                  INNER JOIN servicos AS s ON a.servicos = s.id
                  WHERE a.funcionario = :funcionario 
                  AND a.data = :data 
                  AND (a.hora < :hora_fim AND ADDTIME(a.hora, SEC_TO_TIME(s.tempo * 60)) > :hora_inicio)";

        if (!empty($id)) {
            $query .= " AND a.id != :id";
        }

        $ret = $this->conex->prepare($query);

        $ret->bindParam(':funcionario', $funcionario);
        $ret->bindParam(':data', $data);
        $ret->bindParam(':hora_inicio', $hora_inicio);
        $ret->bindParam(':hora_fim', $hora_fim);

        if (!empty($id)) {
            $ret->bindParam(':id', $id);
        }

        $ret->execute();

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
