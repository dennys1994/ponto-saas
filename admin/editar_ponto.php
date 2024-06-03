<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Incluir arquivo de configuração do banco de dados
require_once '../config/db.php';

// Verificar se o ID do ponto foi fornecido
if (isset($_GET['id'])) {
    $ponto_id = $_GET['id'];

    // Consultar o banco de dados para obter os dados do ponto
    $stmt = $pdo->prepare('SELECT * FROM pontos WHERE id = ?');
    $stmt->execute([$ponto_id]);
    $ponto = $stmt->fetch();

    if (!$ponto) {
        // Redirecionar de volta para a página de gerenciamento de pontos se o ponto não for encontrado
        header('Location: pontos.php');
        exit;
    }
} else {
    // Redirecionar de volta para a página de gerenciamento de pontos se o ID não for fornecido
    header('Location: pontos.php');
    exit;
}

// Processar o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $funcionario_id = $_POST['funcionario_id'];
    $date = $_POST['date'];
    $check_in_time = $_POST['check_in_time'];
    $interval_out_time = $_POST['interval_out_time'];
    $interval_in_time = $_POST['interval_in_time'];
    $check_out_time = $_POST['check_out_time'];

    $check_in = "$date $check_in_time";
    $interval_out = "$date $interval_out_time";
    $interval_in = "$date $interval_in_time";
    $check_out = "$date $check_out_time";

    // Atualizar os dados do ponto no banco de dados
    $stmt = $pdo->prepare('UPDATE pontos SET funcionario_id = ?, check_in = ?, interval_out = ?, interval_in = ?, check_out = ? WHERE id = ?');
    $stmt->execute([$funcionario_id, $check_in, $interval_out, $interval_in, $check_out, $ponto_id]);

    // Redirecionar de volta para a página de gerenciamento de pontos
    header('Location: pontos.php');
    exit;
}

// Consultar o banco de dados para obter a lista de funcionários
$stmt = $pdo->prepare('SELECT id, nome FROM funcionarios WHERE empresa_id = ?');
$stmt->execute([$ponto['empresa_id']]);
$funcionarios = $stmt->fetchAll();
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
                        <a  href="dashboard.php"><i class="fa fa-edit fa-3x"></i>Dashboard</a>
                    </li>
                      <li>
                        <a  href="cadastro_funcionario.php"><i class="fa fa-desktop fa-3x"></i>Cadastro de Funcionário</a>
                    </li>
                    <li>
                        <a  href="consulta_funcionario.php"><i class="fa fa-bar-chart-o fa-3x"></i>Consulta de Funcionário</a>
                    </li>
                    <li>
                        <a  class="active-menu" href="pontos.php"><i class="fa fa-bar-chart-o fa-3x"></i>Gerenciar Pontos</a>
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
                    <h2>Editar Ponto</h2>
    <form action="editar_ponto.php?id=<?= $ponto_id ?>" method="POST">
        <label for="funcionario">Selecione o Funcionário:</label>
        <select id="funcionario" name="funcionario_id" required>
            <?php foreach ($funcionarios as $funcionario): ?>
                <option value="<?= $funcionario['id'] ?>" <?= $funcionario['id'] == $ponto['funcionario_id'] ? 'selected' : '' ?>><?= $funcionario['nome'] ?></option>
            <?php endforeach; ?>
        </select><br>
        <label for="date">Data:</label>
        <input type="date" id="date" name="date" value="<?= date('Y-m-d', strtotime($ponto['check_in'])) ?>" required><br>
        <label for="check_in_time">Hora de Entrada:</label>
        <input type="time" id="check_in_time" name="check_in_time" value="<?= date('H:i', strtotime($ponto['check_in'])) ?>" required><br>
        <label for="interval_out_time">Hora de Saída para Intervalo:</label>
        <input type="time" id="interval_out_time" name="interval_out_time" value="<?= date('H:i', strtotime($ponto['interval_out'])) ?>"><br>
        <label for="interval_in_time">Hora de Retorno do Intervalo:</label>
        <input type="time" id="interval_in_time" name="interval_in_time" value="<?= date('H:i', strtotime($ponto['interval_in'])) ?>"><br>
        <label for="check_out_time">Hora de Saída:</label>
        <input type="time" id="check_out_time" name="check_out_time" value="<?= date('H:i', strtotime($ponto['check_out'])) ?>" required><br>
        <button type="submit">Atualizar Ponto</button>
    </form>
    <a href="pontos.php">Voltar</a>
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