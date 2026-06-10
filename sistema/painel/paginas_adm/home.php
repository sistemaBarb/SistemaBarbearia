<?php
$nivel = $_SESSION['nivel']; //ve se é baberiro ou o adm
$id_logado = $_SESSION['id'];

//Concecta ao banco 
require_once __DIR__ . '/../../../config/database.php';
$database = new Database();
$db = $database->getConnection();

$titulos_grafico = []; //exibe a lista vazia no gráfico
$valores_grafico = [];

if ($nivel == 'administrador') {
    $titulo_tela = "Serviços Mais Vendidos";
    $sql = "SELECT servicos.descricao, COUNT(*) as total 
            FROM agendamentos 
            JOIN servicos ON agendamentos.servicos = servicos.id 
            WHERE agendamentos.status = 'Concluído' 
            GROUP BY servicos.descricao";

    $stmt = $db->query($sql);
} else {
    $titulo_tela = "Minhas Comissões";
    $sql = "SELECT servicos.descricao, SUM(agendamentos.comissao) as total 
            FROM agendamentos 
            JOIN servicos ON agendamentos.servicos = servicos.id 
            WHERE agendamentos.status = 'Concluído' AND agendamentos.funcionario = '$id_logado' 
            GROUP BY servicos.descricao";

    $stmt = $db->query($sql);
}

while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $titulos_grafico[] = $linha['descricao'];
    $valores_grafico[] = $linha['total'];
}
?>

<div class="main-page">
    <div class="bs-example widget-shadow" style="padding: 20px; margin-top: 20px; background: #fff;">
        <h3 class="text-center" style="font-weight: bold; margin-bottom: 20px;"><?php echo $titulo_tela; ?></h3>
        <hr>

        <canvas id="meuGrafico" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const nomes = <?php echo json_encode($titulos_grafico); ?>;
    const valores = <?php echo json_encode($valores_grafico); ?>;

    new Chart(document.getElementById('meuGrafico'), {
        type: 'bar',
        data: {
            labels: nomes,
            datasets: [{
                label: 'Total Acumulado',
                data: valores,
                backgroundColor: '#6A0DAD',
                borderColor: '#4b0082',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>