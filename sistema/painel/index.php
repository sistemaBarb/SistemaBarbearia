<?php
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();
@session_start();


if (!isset($_SESSION['id']) || $_SESSION['id'] == "") { //verifica se tem algum alguem ID já criado, se não joga para a tela de login
    echo "<script>window.location='../index.php'</script>";
    exit();
}

require_once '../../config/database.php';
$database = new Database();
$pdo = $database->getConnection();

$id_usuario = $_SESSION['id'];

$query = $pdo->query("SELECT * from usuarios where id ='$id_usuario'"); // verifica as informações criadas na tela de login no banco, paraa montar de acordo com quem acessou 
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
    $nome_usuario = $res[0]['nome'];
    $email_usuario = $res[0]['email'];
    $cpf_usuario = $res[0]['cpf'];
    $nivel_usuario = $res[0]['nivel'];
    $telefone_usuario = $res[0]['telefone'];
    $foto = 'sem-foto.jpg';
}
$pag = 'home';
if (@$_GET['pag'] == "") {
    $pag = 'home';
} else {
    $pag = $_GET['pag'];
}



?>

<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>

<head>
    <title>Sistema Barbearia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Glance Design Dashboard Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
    <script type="application/x-javascript">
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />

    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />

    <!-- font-awesome icons CSS -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- //font-awesome icons CSS-->

    <!-- side nav css file -->
    <link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css' />
    <!-- //side nav css file -->

    <!-- js-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>

    <!--webfonts-->
    <link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
    <!--//webfonts-->

    <!-- chart -->
    <script src="js/Chart.js"></script>
    <!-- //chart -->

    <!-- Metis Menu -->
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <link href="css/custom.css" rel="stylesheet">
    <!--//Metis Menu -->
    <style>
        #chartdiv {
            width: 100%;
            height: 295px;
        }
    </style>
    <!--pie-chart --><!-- index page sales reviews visitors pie chart -->
    <script src="js/pie-chart.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#demo-pie-1').pieChart({
                barColor: '#2dde98',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function(from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-2').pieChart({
                barColor: '#8e43e7',
                trackColor: '#eee',
                lineCap: 'butt',
                lineWidth: 8,
                onStep: function(from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-3').pieChart({
                barColor: '#ffc168',
                trackColor: '#eee',
                lineCap: 'square',
                lineWidth: 8,
                onStep: function(from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });


        });
    </script>
    <!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->

    <!-- requried-jsfiles-for owl -->

    <!-- //requried-jsfiles-for owl -->
</head>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
            <!--left-fixed -navigation-->
            <aside class="sidebar-left">
                <nav class="navbar navbar-inverse">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <h1>
                            <a class="navbar-brand" href="index.php" style="font-size: 19px; white-space: nowrap;">
                                PAINEL <?php echo ($_SESSION['nivel'] == 'administrador') ? 'ADM' : 'BARBEIRO'; ?>
                                <span class="dashboard_text" style="font-size: 12px; margin-top: 2px;">Sistema Barbearia</span>
                            </a>
                        </h1>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="sidebar-menu">
                            <li class="header">Menu Navegação</li>
                            <li class="treeview">
                                <a href="index.php">
                                    </i> <span>Home</span>
                                </a>
                            </li>

                            <?php if ($nivel_usuario == 'administrador') { ?>
                                <li class="treeview">
                                    <a href="index.php?pag=barbeiros">
                                        </i> <span>Barbeiros</span>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($nivel_usuario == 'administrador') { ?>
                                <li class="treeview">
                                    <a href="index.php?pag=usuarios">
                                        </i> <span>Clientes</span>
                                    </a>
                                </li>
                            <?php } ?>

                            <li class="treeview">
                                <a href="index.php?pag=agendamentos">
                                    </i> <span>Agendamentos</span>
                                </a>
                            </li>

                            <?php if ($nivel_usuario == 'administrador') { ?>
                                <li class="treeview">
                                    <a href="index.php?pag=servicos">
                                        </i> <span>Serviços</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </nav>
            </aside>
        </div>

        <div class="sticky-header header-section ">

            <div class="header-right">



                <div class="profile_details">
                    <ul>
                        <li class="dropdown profile_details_drop">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <div class="profile_img">
                                    <span class="prfil-img"><img src="images/2.jpg" alt=""> </span>
                                    <div class="user-name">
                                        <p><?php echo $nome_usuario ?></p>
                                        <span><?php echo $nivel_usuario ?></span>
                                    </div>
                                    <span style="margin-left: 8px; margin-top: 5px; font-size: 40px; color: #999;"></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                            <ul class="dropdown-menu drp-mnu">
                                <li><a href="#" data-toggle="modal" data-target="#modalPerfil"><span style="margin-right: 8px;"></span> Editar Perfil</a></li>
                                <li><a href="logout.php"><span style="margin-right: 8px;"></span> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="clearfix"> </div>
        </div>








        <!-- //header-ends -->
        <!---728x90--->
        <!-- main content start-->
        <div id="page-wrapper">
            <?php
            switch ($pag) {
                case 'home':
                    require_once("paginas_adm/home.php");
                    break;

                case 'usuarios':
                    require_once("paginas_adm/usuarios/usuarios.php");
                    break;

                case 'agendamentos':
                    require_once("paginas_adm/agendamentos/agendamentos.php");
                    break;



                case 'barbeiros':
                    require_once("paginas_adm/barbeiros/barbeiros.php");
                    break;


                case 'servicos':
                    require_once("paginas_adm/servicos/servicos.php");
                    break;

                default:
                    require_once("paginas_adm/home.php");
                    break;
            }
            ?>

        </div>





        <!--footer-->
        <div class="footer">

            <p class="mb-0">© 2026 Barbearia do Luiz</p>
            <p>Desenvolvido para demonstração de PFC</p>

        </div>
        <!--//footer-->
    </div>
    <!-- new added graphs chart js-->

    <script src="js/Chart.bundle.js"></script>
    <script src="js/utils.js"></script>

    <script>
        var MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var color = Chart.helpers.color;
        var barChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
                label: 'Dataset 1',
                backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                borderColor: window.chartColors.red,
                borderWidth: 1,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ]
            }, {
                label: 'Dataset 2',
                backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 1,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ]
            }]

        };

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Chart.js Bar Chart'
                    }
                }
            });

        };

        document.getElementById('randomizeData').addEventListener('click', function() {
            var zero = Math.random() < 0.2 ? true : false;
            barChartData.datasets.forEach(function(dataset) {
                dataset.data = dataset.data.map(function() {
                    return zero ? 0.0 : randomScalingFactor();
                });

            });
            window.myBar.update();
        });

        var colorNames = Object.keys(window.chartColors);
        document.getElementById('addDataset').addEventListener('click', function() {
            var colorName = colorNames[barChartData.datasets.length % colorNames.length];;
            var dsColor = window.chartColors[colorName];
            var newDataset = {
                label: 'Dataset ' + barChartData.datasets.length,
                backgroundColor: color(dsColor).alpha(0.5).rgbString(),
                borderColor: dsColor,
                borderWidth: 1,
                data: []
            };

            for (var index = 0; index < barChartData.labels.length; ++index) {
                newDataset.data.push(randomScalingFactor());
            }

            barChartData.datasets.push(newDataset);
            window.myBar.update();
        });

        document.getElementById('addData').addEventListener('click', function() {
            if (barChartData.datasets.length > 0) {
                var month = MONTHS[barChartData.labels.length % MONTHS.length];
                barChartData.labels.push(month);

                for (var index = 0; index < barChartData.datasets.length; ++index) {
                    //window.myBar.addData(randomScalingFactor(), index);
                    barChartData.datasets[index].data.push(randomScalingFactor());
                }

                window.myBar.update();
            }
        });

        document.getElementById('removeDataset').addEventListener('click', function() {
            barChartData.datasets.splice(0, 1);
            window.myBar.update();
        });

        document.getElementById('removeData').addEventListener('click', function() {
            barChartData.labels.splice(-1, 1); // remove the label first

            barChartData.datasets.forEach(function(dataset, datasetIndex) {
                dataset.data.pop();
            });

            window.myBar.update();
        });
    </script>
    <!-- new added graphs chart js-->

    <!-- Classie --><!-- for toggle left push menu script -->
    <script src="js/classie.js"></script>
    <script>
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
            showLeftPush = document.getElementById('showLeftPush'),
            body = document.body;

        showLeftPush.onclick = function() {
            classie.toggle(this, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
            classie.toggle(menuLeft, 'cbp-spmenu-open');
            disableOther('showLeftPush');
        };


        function disableOther(button) {
            if (button !== 'showLeftPush') {
                classie.toggle(showLeftPush, 'disabled');
            }
        }
    </script>
    <!-- //Classie --><!-- //for toggle left push menu script -->

    <!--scrolling js-->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <!--//scrolling js-->

    <!-- side nav js -->
    <script src='js/SidebarNav.min.js' type='text/javascript'></script>
    <script>
        $('.sidebar-menu').SidebarNav()
    </script>

    <script src="js/bootstrap.js"> </script>


    <div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-labelledby="modalPerfilLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-dark text-white">
                    <h4 class="modal-title font-weight-bold" id="modalPerfilLabel">Editar Meu Perfil</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="margin-top: -20px;">
                        <span aria-hidden="true"></span>
                    </button>
                </div>

                <form method="POST" action="editar_adm.php">
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Nome</label>
                            <input type="text" class="form-control" name="nome" value="<?php echo @$nome_usuario; ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">E-mail</label>
                            <input type="email" class="form-control" name="email" value="<?php echo @$_SESSION['email']; ?>" placeholder="Seu e-mail">
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Nova Senha</label>
                            <input type="password" class="form-control" name="senha" placeholder="Caso queira mantar a senha que já utilize é so não inserir nada ">
                            <small class="text-muted">Apenas preencha se desejar alterar a sua senha de acesso.</small>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary shadow-sm">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>