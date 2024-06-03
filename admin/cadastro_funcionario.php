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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do formulário
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    // Inserir o novo funcionário no banco de dados
    $stmt = $pdo->prepare('INSERT INTO funcionarios (empresa_id, nome, cpf, email, telefone, endereco) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$empresa_id, $nome, $cpf, $email, $telefone, $endereco]);

    // Redirecionar para a página de consulta após o cadastro
    header('Location: consulta_funcionario.php');
    exit;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tela Administrativo</title>
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
                        <a  class="active-menu" href="cadastro_funcionario.php"><i class="fa fa-desktop fa-3x"></i>Cadastro de Funcionário</a>
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
            <h2>Cadastro de Funcionário</h2>
          
            <form action="cadastro_funcionario.php" method="POST">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome"class="form-control" placeholder="Nome Funcionario" />
                </div>
                <div class="form-group">
                    <label for="nome">CPF:</label>
                    <input type="text" id="cpf" name="cpf" required class="form-control" placeholder="CPF funcionario" />
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required class="form-control" placeholder="Email funcionario" />
                </div>
                <div class="form-group">
                    <label  for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" required class="form-control" placeholder="Telefone funcionario" />
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input id="endereco" name="endereco" required class="form-control" placeholder="Endereço funcionario" />
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>

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