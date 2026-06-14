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
        $query = "SELECT * FROM usuarios WHERE nivel = 'barbeiro' AND ativo = 'sim' ORDER BY nome ASC";
        $ret = $this->conex->prepare($query);
        $ret->execute();
        return $ret->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrarBarbeiro($nome, $email, $cpf, $telefone, $senha)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $query = "INSERT INTO " . $this->table_name . " 
                 (nome, email, cpf, telefone, senha_cr, nivel, ativo, data_cadastro) 
                 VALUES 
                 (:nome, :email, :cpf, :telefone, :senha, 'barbeiro', 'sim', CURDATE())";

        $ret = $this->conex->prepare($query);

        $ret->bindParam(':nome', $nome);
        $ret->bindParam(':email', $email);
        $ret->bindParam(':cpf', $cpf);

        $ret->bindParam(':telefone', $telefone);
        $ret->bindParam(':senha', $senha_hash);

        return $ret->execute();
    }

    public function excluirBarbeiro($id)
    {
        $query = "UPDATE " . $this->table_name . " SET ativo = 'nao' WHERE id = :id";
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

    public function registrarLogAcesso($admin_id, $barbeiro_id)
    {
        $query = "INSERT INTO logs_acesso_dados (admin_id, cliente_id) VALUES (:admin_id, :cliente_id)";
        $ret = $this->conex->prepare($query);
        $ret->bindParam(':admin_id', $admin_id);
        $ret->bindParam(':cliente_id', $barbeiro_id);
        return $ret->execute();
    }

    public function buscarPorId($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $ret = $this->conex->prepare($query);
        $ret->bindParam(':id', $id);
        $ret->execute();
        return $ret->fetch(PDO::FETCH_ASSOC);
    }
}
