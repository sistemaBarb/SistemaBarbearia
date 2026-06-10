<?php

class database
{
    private $host = "localhost";
    private $db_name = "barbearia"; // Confirme se é este o nome da sua base de dados
    private $username = "root";
    private $password = "";
    public $conex; //busca os dados do BD


    public function getConnection()
    { // função que busca e tras os dados do BD
        $this->conex = null;

        try {
            $this->conex = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);

            $this->conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "erro de conexão: " . $exception->getMessage();
        }

        return $this->conex;
    }
}
