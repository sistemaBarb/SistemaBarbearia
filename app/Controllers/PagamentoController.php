<?php

class PagamentoController
{
    // Token de acesso (Chave mestra de conexão com o Mercado Pago)
    private $tokenMercadoPago = 'APP_USR-5589235026089854-060616-02fd71e2fdd721d8b4566807cd2be157-3456266690';

    public function gerarLinkPagamento()
    {
        // Garante que a requisição veio de um formulário POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../sistema/painel/index.php");
            exit;
        }


        $idAgendamento = $_POST['id_agendamento'];
        $valorServico  = (float) $_POST['valor_servico'];


        $dadosFatura = [ //preparação dos dados 
            "items" => [
                [
                    "title"       => "Serviço de Barbearia - Código #" . $idAgendamento,
                    "quantity"    => 1,
                    "currency_id" => "BRL",
                    "unit_price"  => $valorServico
                ]
            ],

            "external_reference" => $idAgendamento,

            "back_urls" => [
                "success" => "https://localhost/barbearia/sistema/painel/index.php?pag=agendamentos",
                "failure" => "https://localhost/barbearia/sistema/painel/index.php?pag=agendamentos",
                "pending" => "https://localhost/barbearia/sistema/painel/index.php?pag=agendamentos"
            ],
            "auto_return" => "approved" // Retorna ao sistema sozinho se o pagamento for aprovado
        ];

        $conexaoAPI = curl_init('https://api.mercadopago.com/checkout/preferences'); //abre a conexao com a api 

        //regras de envio da conexão
        curl_setopt_array($conexaoAPI, [
            CURLOPT_RETURNTRANSFER => true, // API devolve uma resposta escrita
            CURLOPT_POST           => true, //envio como POST
            CURLOPT_POSTFIELDS     => json_encode($dadosFatura), //transforma os dados em texto JSON
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer " . $this->tokenMercadoPago, //identifica a barbearia na API
                "Content-Type: application/json" //envio de dados em JSON
            ]
        ]);
        $respostaTexto = curl_exec($conexaoAPI); //guarda o q o mercado pago respondeu 

        //fecha a conexão aberta
        curl_close($conexaoAPI);


        $respostaObjeto = json_decode($respostaTexto); //retorna legivel para o php ler

        // Se a API criou a cobrança com sucesso, ela devolve o endereço "init_point"
        if (isset($respostaObjeto->init_point)) {
            // Empurra o usuário para a tela oficial de pagamento do Mercado Pago
            header("Location: " . $respostaObjeto->init_point);
            exit;
        } else {
            echo "<script>
                    window.alert('ocorreu uma erro na ligação com o gateway de pagamento');
                    window.location='../sistema/painel/index.php?pag=agendamentos';
                  </script>";
            exit;
        }
    }
}
