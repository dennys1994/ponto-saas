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

// Consultar o banco de dados para obter os funcionários da empresa
$stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE empresa_id = ?');
$stmt->execute([$empresa_id]);
$funcionarios = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $funcionario_id = $_POST['funcionario_id'];
    $date = $_POST['date'];
    $check_in_time = $_POST['check_in_time'];
    $interval_out_time = $_POST['interval_out_time'];
    $interval_in_time = $_POST['interval_in_time'];
    $check_out_time = $_POST['check_out_time'];
    $photo = file_get_contents($_FILES['photo']['tmp_name']);

    $check_in = "$date $check_in_time";
    $interval_out = !empty($interval_out_time) ? "$date $interval_out_time" : null;
    $interval_in = !empty($interval_in_time) ? "$date $interval_in_time" : null;
    $check_out = "$date $check_out_time";

    // Verificar se é uma edição ou um novo ponto
    if (isset($_POST['ponto_id'])) {
        $ponto_id = $_POST['ponto_id'];
        $stmt = $pdo->prepare('UPDATE pontos SET check_in = ?, interval_out = ?, interval_in = ?, check_out = ?, photo = ? WHERE id = ?');
        $stmt->execute([$check_in, $interval_out, $interval_in, $check_out, $photo, $ponto_id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO pontos (empresa_id, funcionario_id, check_in, interval_out, interval_in, check_out, photo) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$empresa_id, $funcionario_id, $check_in, $interval_out, $interval_in, $check_out, $photo]);
    }
}
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
                    <h2>Gerenciar Pontos</h2>
            <form action="pontos.php" method="POST" enctype="multipart/form-data">
                <label for="funcionario">Selecione o Funcionário:</label>
                <select id="funcionario" name="funcionario_id" required class="form-control">
                    <?php foreach ($funcionarios as $funcionario): ?>
                        <option value="<?= $funcionario['id'] ?>"><?= $funcionario['nome'] ?></option>
                    <?php endforeach; ?>
                </select><br>
                <label for="date">Data:</label>
                <input type="date" id="date" name="date" required class="form-control"><br>
                <label for="check_in_time">Hora de Check-in:</label>
                <input type="time" id="check_in_time" name="check_in_time" required class="form-control"><br>
                <label for="interval_out_time">Hora de Saída para Intervalo:</label>
                <input type="time" id="interval_out_time" name="interval_out_time" class="form-control"><br>
                <label for="interval_in_time">Hora de Volta do Intervalo:</label>
                <input type="time" id="interval_in_time" name="interval_in_time" class="form-control"><br>
                <label for="check_out_time">Hora de Check-out:</label>
                <input type="time" id="check_out_time" name="check_out_time" class="form-control"><br>
                <label for="photo" class="form-label">Foto:</label>
                <input type="file" id="photo" name="photo" accept="image/*" class="form-control"><br>
                <button type="submit">Salvar Ponto</button>
            </form>
            
            <h2>Pontos Cadastrados</h2>
            <?php
            $stmt = $pdo->prepare('SELECT pontos.*, funcionarios.nome FROM pontos JOIN funcionarios ON pontos.funcionario_id = funcionarios.id WHERE funcionarios.empresa_id = ?');
            $stmt->execute([$empresa_id]);
            $pontos = $stmt->fetchAll();
            ?>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">                    
                <thead>
                    <tr>
                        <th>Funcionário</th>
                        <th>Entrada</th>
                        <th>Saída Intervalo</th>
                        <th>Volta Intervalo</th>
                        <th>Saída</th>
                        <th>Foto</th>  
                        <th style="text-align: center;">Ações</th>                 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pontos as $ponto): ?>
                        <tr>
                            <td><?= $ponto['nome'] ?></td>
                            <td><?= $ponto['check_in'] ?></td>
                            <td><?= $ponto['interval_out'] ?></td>
                            <td><?= $ponto['interval_in'] ?></td>
                            <td><?= $ponto['check_out'] ?></td>
                            <td><img src="data:image/jpeg;base64,<?= base64_encode($ponto['photo']) ?>" width="50" height="50" /></td>
                            <td>
                                <form action="pontos.php" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="ponto_id" value="<?= $ponto['id'] ?>">
                                    <input type="hidden" name="funcionario_id" value="<?= $ponto['funcionario_id'] ?>">
                                    <input type="hidden" name="check_in" value="<?= $ponto['check_in'] ?>">
                                    <input type="hidden" name="interval_out" value="<?= $ponto['interval_out'] ?>">
                                    <input type="hidden" name="interval_in" value="<?= $ponto['interval_in'] ?>">
                                    <input type="hidden" name="check_out" value="<?= $ponto['check_out'] ?>">
                                </form>
                                
                                    <a href="editar_ponto.php?id=<?= $ponto['id'] ?>"class="btn btn-primary"><i class="fa fa-edit "></i> Editar</a> | 
                                    <a href="excluir_ponto.php?id=<?= $ponto['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este ponto?')"class="btn btn-danger"><i class="fa fa-trash-o"></i>Excluir</a>
                                
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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