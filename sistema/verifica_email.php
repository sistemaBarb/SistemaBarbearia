<?php
require_once __DIR__ . '/../app/Controllers/UsuarioController.php';

$mensagem = "Nenhum token foi informado.";
$tipo_alerta = "alert-warning";

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../app/Models/Usuario.php';
    $database = new Database();
    $db = $database->getConnection();
    $usuario = new Usuario($db);


    if ($usuario->ativarEmailUsuario($token)) {
        $mensagem = "Seu e-mail está validado, sua conta está liberada.";
        $tipo_alerta = "alert-success";
    } else {
        $mensagem = "Não foi possível validar sua conta";
        $tipo_alerta = "alert-danger";
    }
}
