<?php

class database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conex;

    public function __construct()
    {
        $this->host = $_SERVER['DB_HOST'];
        $this->db_name = $_SERVER['DB_NAME'];
        $this->username = $_SERVER['DB_USER'];
        $this->password = $_SERVER['DB_PASS'];
    }

    public function getConnection()
    {
        $this->conex = null;

        try {
            $this->conex = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erro de conexão: " . $exception->getMessage();
        }

        return $this->conex;
    }
}
