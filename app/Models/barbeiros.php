<?php

class Barbeiro
{
    private $conex;
    private $table_name = "usuarios";

    public function __construct($db)
    {
        $this->conex = $db;
    }

    public function listarBarbeiros()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nivel = 'barbeiro' ORDER BY nome ASC";


        $ret = $this->conex->prepare($query);
        $ret->execute();
        return $ret->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrarBarbeiro($nome, $email, $cpf, $telefone, $senha)
    {

        $query = "INSERT INTO " . $this->table_name . " (nome, email, cpf, telefone, senha_cr, nivel) VALUES (:nome, :email, :cpf, :telefone, :senha, 'barbeiro')";
        $ret = $this->conex->prepare($query);

        // Proteção e hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $ret->bindParam(':nome', $nome);

        $ret->bindParam(':email', $email);

        $ret->bindParam(':cpf', $cpf);

        $ret->bindParam(':telefone', $telefone);

        $ret->bindParam(':senha', $senha_hash); // O apelido :senha continua igual, sem problemas

        return $ret->execute();
    }

    public function excluirBarbeiro($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $ret = $this->conex->prepare($query);

        $ret->bindParam(':id', $id);

        return $ret->execute();
    }


    public function EditarBarbeiro($id, $nome, $email, $cpf, $telefone)
    {
        $query = "UPDATE " . $this->table_name . " SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone WHERE id = :id";
        $ret = $this->conex->prepare($query);

        $ret->bindParam(':nome', $nome);
        $ret->bindParam(':email', $email);
        $ret->bindParam(':cpf', $cpf);
        $ret->bindParam(':telefone', $telefone);
        $ret->bindParam(':id', $id);

        return $ret->execute();
    }
}
