<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
@session_start();
if (!isset($_SESSION['id']) || $_SESSION['id'] == "") { //id de quem está logado 
    echo "<script>window.location='sistema/index.php'</script>";
    exit();
}
require_once 'config/database.php';
$database = new Database();
$pdo = $database->getConnection();

$query_servicos = $pdo->query("SELECT id, descricao, valor FROM servicos ORDER BY descricao ASC"); //busca os serviços que estão no banco de dados
$servicos = $query_servicos->fetchAll(PDO::FETCH_ASSOC);

$query_barb = $pdo->query("SELECT id, nome FROM usuarios WHERE nivel = 'barbeiro' ORDER BY nome ASC"); // busca os barbeiros 
$barbeiros = $query_barb->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbearia do Luiz - Agende seu Horário</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --cor-primaria: #6A0DAD;
            --cor-secundaria: #4b0082;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background-color: var(--cor-primaria);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(106, 13, 173, 0.9) 0%, rgba(75, 0, 130, 0.9) 100%), url('https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
            color: white;
            padding: 80px 0;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            margin-bottom: -50px;
        }

        .card-agendamento {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: white;
            padding: 30px;
        }

        .btn-custom {
            background-color: var(--cor-primaria);
            color: white;
            border-radius: 8px;
            padding: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--cor-secundaria);
            color: white;
            transform: translateY(-2px);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"></i>Barbearia do Luiz</a>
        </div>
    </nav>

    <section class=" hero-section text-center">
        <div class="container pb-5">
            <h4 class="text-center text-white mb-3">
                Olá, <?php echo $_SESSION['nome']; ?>!
            </h4>
            <p class="lead mb-4">Agende o seu corte de forma rápida e sem complicações.</p>
        </div>
    </section>

    <section class="container" style="position: relative; z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card-agendamento">
                    <h3 class="text-center mb-4" style="color: var(--cor-primaria);">Reserve seu Horário</h3>

                    <form action="conectar_agendamento.php" method="POST">
                        <div class="row g-3">

                            <input type="hidden" name="nome_cliente" value="<?php echo $_SESSION['nome']; ?>">

                            <div class="col-md-12">
                                <label class="form-label text-muted"><i class="fas fa-phone me-2"></i>Insira seu Telefone</label>
                                <input type="tel" class="form-control" name="telefone" placeholder="(11) 90000-0000" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted"><i class="fas fa-list me-2"></i>Serviço Desejado</label>
                                <select class="form-select" name="servico" required>
                                    <option value="" selected disabled>Escolha um serviço...</option>

                                    <?php foreach ($servicos as $servico) { ?>
                                        <option value="<?php echo $servico['id']; ?>">
                                            <?php echo $servico['descricao']; ?> - R$ <?php echo number_format($servico['valor'], 2, ',', '.'); ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted"><i class="fas fa-cut me-2"></i>Barbeiro</label>
                                <select class="form-select" name="funcionario" required>
                                    <option value="" selected disabled>Escolha seu barbeiro</option>

                                    <?php foreach ($barbeiros as $barbeiro) { ?>
                                        <option value="<?php echo $barbeiro['id']; ?>">
                                            <?php echo $barbeiro['nome']; ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted"><i class="fas fa-calendar-alt me-2"></i>Data</label>
                                <input type="date" name="data" id="data" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted"><i class="fas fa-clock me-2"></i>Horário</label>
                                <input type="time" class="form-control" name="hora" required>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-custom w-100 fs-5">
                                    Confirmar Agendamento <i class="fas fa-check-circle ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </section>

    <footer class="text-center mt-5 py-4 text-muted">
        <div class="container">
            <p class="mb-0">© 2026 Barbearia do Luiz</p>
            <small>Desenvolvido para demonstração de PFC</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>