<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Incluir arquivo de configuração do banco de dados
require_once '../config/db.php';

// Obter o ID da empresa do usuário logado
$empresa_id = $_SESSION['empresa_id'];

// Consultar o banco de dados para obter um resumo dos funcionários e das últimas atividades de ponto
$stmt = $pdo->prepare('SELECT COUNT(*) AS total_funcionarios FROM funcionarios WHERE empresa_id = ?');
$stmt->execute([$empresa_id]);
$total_funcionarios = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT * FROM pontos WHERE empresa_id = ? ORDER BY check_in DESC LIMIT 5');
$stmt->execute([$empresa_id]);
$ultimas_atividades = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT funcionarios.nome AS nome_funcionario, pontos.check_in, pontos.check_out FROM pontos INNER JOIN funcionarios ON pontos.funcionario_id = funcionarios.id WHERE pontos.empresa_id = ? ORDER BY pontos.check_in DESC LIMIT 5');
$stmt->execute([$empresa_id]);
$ultimas_atividades = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coelho Administrativo</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Administrativo</a> 
            </div>
  <div style="color: white;
padding: 15px 50px 5px 50px;
float: right;
font-size: 16px;"> Último acesso : &nbsp; <a href="logout.php" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				<li class="text-center">
                    <img src="assets/img/find_user.png" class="user-image img-responsive"/>
					</li>
                    <li>
                        <a class="active-menu" href="dashboard.php"><i class="fa fa-edit fa-3x"></i>Dashboard</a>
                    </li>
                      <li>
                        <a  href="cadastro_funcionario.php"><i class="fa fa-desktop fa-3x"></i>Cadastro de Funcionário</a>
                    </li>
                    <li>
                        <a  href="consulta_funcionario.php"><i class="fa fa-bar-chart-o fa-3x"></i>Consulta de Funcionário</a>
                    </li>
                    <li>
                        <a  href="pontos.php"><i class="fa fa-bar-chart-o fa-3x"></i>Gerenciar Pontos</a>
                    </li>
                    <li>
                        <a  href="relatorios.php"><i class="fa fa-bar-chart-o fa-3x"></i>Relatórios</a>
                    </li>			               	
                </ul>
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                    <main>
            <h2>Dashboard</h2>
            <div>
                <h3>Resumo</h3>
                <p>Total de Funcionários: <?= $total_funcionarios ?></p>
            </div>
            <div>
                <h3>Últimas Atividades de Ponto</h3>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Funcionário</th>
                            <th>Entrada</th>
                            <th>Saída</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimas_atividades as $atividade): ?>
                            <tr>
                                <td><?= $atividade['nome_funcionario'] ?></td>
                                <td><?= $atividade['check_in'] ?></td>
                                <td><?= $atividade['check_out'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>
