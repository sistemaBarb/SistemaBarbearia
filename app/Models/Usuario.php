<?php


class Usuario
{


    private $chave_criptografia = 'BarbeariaTCC2026_Seguranca';

    public function criptografarCPF($cpf)
    {
        return openssl_encrypt($cpf, 'aes-128-ecb', $this->chave_criptografia); //AES-128 para criptografar o CPF 
    }

    public function descriptografarCPF($cpf_criptografado)
    {
        return openssl_decrypt($cpf_criptografado, 'aes-128-ecb', $this->chave_criptografia); // descriptografa apenas para leitura, e o usuario poder logar
    }

    private $conex;
    private $table_name = "usuarios";


    public $id;
    public $nome;
    public $email;
    public $cpf;
    public $senha_cr;
    public $nivel;
    public $ativo;
    public $token;

    //recebe a conexão da classe do banco de dados 
    public function __construct($db)
    {
        $this->conex = $db;
    }




    public function cadastrarCliente($nome, $cpf, $email, $senha, $token)
    {

        $query = "INSERT INTO " . $this->table_name . " (nome, cpf, email, senha_cr, nivel, ativo, email_verificado, token_verificacao) 
                  VALUES (:nome, :cpf, :email, :senha, 'cliente', 'sim', 0, :token)";

        $ret = $this->conex->prepare($query);
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $cpf_seguro = $this->criptografarCPF($cpf);

        $ret->bindParam(':nome', $nome);
        $ret->bindParam(':cpf', $cpf);
        $ret->bindParam(':email', $email);
        $ret->bindParam(':senha', $senha_hash);
        $ret->bindParam(':token', $token);

        return $ret->execute();
    }

    //verificar o email do ususario 
    public function ativarEmailUsuario($token)
    {

        $query = "UPDATE " . $this->table_name . " SET email_verificado = 1, token_verificacao = NULL WHERE token_verificacao = :token";

        $ret = $this->conex->prepare($query);

        $ret->bindParam(':token', $token);
        $ret->execute();


        return  $ret->rowCount() > 0;
    }



    //Login
    public function buscarPorEmailOuCpf($login)
    {
        $loginCriptografado = $this->criptografarCPF($login);
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :login OR cpf = :login LIMIT 1";

        $ret = $this->conex->prepare($query);
        $login = htmlspecialchars(strip_tags($login)); //limoa o campo para proteger contra SQL INJECTION

        $ret->bindParam(':login', $login);
        $ret->bindParam(':login_cpf', $loginCriptografado);
        $ret->execute();

        return $ret->fetch(PDO::FETCH_ASSOC);
    }

    public function listarClientes()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nivel = 'cliente' AND ativo = 'sim' ORDER BY nome ASC";

        $ret = $this->conex->prepare($query);
        $ret->execute();
        return $ret->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluirUsuario($id)
    {
        $query = "UPDATE " . $this->table_name . " SET ativo = 'nao' WHERE id = :id";
        $ret = $this->conex->prepare($query);
        $ret->bindParam(':id', $id);

        return $ret->execute();
    }


    public function EditarUsuario($id, $nome, $email, $cpf, $ativo)
    {
        $query = "UPDATE " . $this->table_name . " SET nome = :nome, email = :email, cpf = :cpf, ativo = :ativo WHERE id = :id";

        $ret = $this->conex->prepare($query);

        $ret->bindParam(':nome', $nome);
        $ret->bindParam(':email', $email);

        $ret->bindParam(':cpf', $cpf);
        $ret->bindParam(':ativo', $ativo);
        $ret->bindParam(':id', $id);

        return $ret->execute();
    }


    //sessão para fazer o reset de senha 

    public function verificarEmailExiste($email)
    {
        $query = "SELECT id, nome FROM " . $this->table_name . " WHERE email = :email LIMIT 1"; // verifica se o email já existe 
        $ret = $this->conex->prepare($query);

        $ret->bindParam(':email', $email);
        $ret->execute();
        return $ret->fetch(PDO::FETCH_ASSOC);
    }
    public function salvarTokenRecuperacao($email, $token)
    {
        $query = "INSERT INTO reset_senha (email, token, usado, validade) VALUES (:email, :token, 0, DATE_ADD(NOW(), INTERVAL 1 HOUR))";

        $ret = $this->conex->prepare($query);

        $ret->bindParam(':email', $email);
        $ret->bindParam(':token', $token);
        return $ret->execute();
    }

    public function validarTokenRecuperacao($token)
    {
        $query = "SELECT email FROM reset_senha WHERE token = :token AND usado = 0 AND validade > NOW()"; //ve se o token é valido no BD 

        $ret = $this->conex->prepare($query);
        $ret->bindParam(':token', $token);
        $ret->execute();

        return $ret->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarSenhaRecuperacao($email, $novaSenha, $token)
    {
        try {
            $this->conex->beginTransaction();

            $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $queryUser = "UPDATE " . $this->table_name . " SET senha_cr = :senha WHERE email = :email"; //atualiza a senha 

            $retUser = $this->conex->prepare($queryUser);

            $retUser->bindParam(':senha', $hash);
            $retUser->bindParam(':email', $email);
            $retUser->execute();


            $queryToken = "UPDATE reset_senha SET usado = 1 WHERE token = :token"; // descarta o token para não ser usado de novo

            $Token = $this->conex->prepare($queryToken);

            $Token->bindParam(':token', $token);
            $Token->execute();

            $this->conex->commit();

            return true;
        } catch (Exception $e) {
            $this->conex->rollBack();
            return false;
        }
    }

    public function registrarLogAcesso($admin_id, $cliente_id)
    {
        $query = "INSERT INTO logs_acesso_dados (admin_id, cliente_id) VALUES (:admin_id, :cliente_id)";
        $ret = $this->conex->prepare($query);
        $ret->bindParam(':admin_id', $admin_id);
        $ret->bindParam(':cliente_id', $cliente_id);
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
