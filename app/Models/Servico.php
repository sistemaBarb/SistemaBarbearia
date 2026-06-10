<?php
class Servico
{
    private $conex;
    private $table_name = "servicos";

    public function __construct($db)
    {
        $this->conex = $db;
    }

    public function listar()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $ret = $this->conex->prepare($query);
        $ret->execute();

        return $ret->fetchAll(PDO::FETCH_ASSOC); // Devolve os dados como um array
    }

    public function cadastrar($descricao, $valor, $tempo)
    {
        $query = "INSERT INTO " . $this->table_name . " SET descricao = :descricao, valor = :valor, tempo = :tempo";
        $ret = $this->conex->prepare($query);

        $ret->bindParam(':descricao', $descricao);
        $ret->bindParam(':valor', $valor);
        $ret->bindParam(':tempo', $tempo);

        return $ret->execute();
    }

    public function editar($id, $descricao, $valor, $tempo)
    {
        $query = "UPDATE " . $this->table_name . " SET descricao = :descricao, valor = :valor, tempo = :tempo WHERE id = :id";
        $ret = $this->conex->prepare($query);


        $ret->bindParam(':descricao', $descricao);
        $ret->bindParam(':valor', $valor);
        $ret->bindParam(':tempo', $tempo);
        $ret->bindParam(':id', $id);

        return $ret->execute();
    }

    public function excluir($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conex->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
