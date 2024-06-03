<?php
// Incluir arquivo de configuração do banco de dados
require_once '../config/db.php';

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar os dados do formulário
$nome = $_POST['nome'];
$cnpj = $_POST['cnpj'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];

// Inserir a nova empresa no banco de dados mestre
$stmt = $pdo->prepare('INSERT INTO empresas (nome, cnpj, email, telefone, endereco) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([$nome, $cnpj, $email, $telefone, $endereco]);

// Inserir usuário padrão para a empresa
$password_hash = password_hash($cnpj, PASSWORD_DEFAULT); // Hash da senha (o CNPJ)
$stmt_user = $pdo->prepare('INSERT INTO usuarios_empresa (empresa_id, username, password_hash) VALUES ((SELECT id FROM empresas WHERE cnpj = ?), ?, ?)');
$stmt_user->execute([$cnpj, $cnpj, $password_hash]);


}

// Selecionar todas as empresas cadastradas
$stmt = $pdo->query('SELECT * FROM empresas');
$empresas = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tela Master</title>
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
font-size: 16px;"> Último acesso :  &nbsp; <a href="logout.php" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				<li class="text-center">
                    <img src="assets/img/find_user.png" class="user-image img-responsive"/>
					</li>
                    <li>
                        <a class="active-menu" href="index.php"><i class="fa fa-edit fa-3x"></i>Cadastro empresas</a>
                    </li>
                      <li>
                        <a  href="#"><i class="fa fa-desktop fa-3x"></i>Consulta Pedidos</a>
                    </li>                		               	
                </ul>
               
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                    <div class="content">
            <nav>
                <h2>Empresas Cadastradas</h2>
                <ul>
                    <?php foreach ($empresas as $empresa): ?>
                        <li><?= $empresa['nome'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            
            <h2>Adicionar Nova Empresa</h2>
            <form action="index.php" method="POST">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required><br>
                <label for="cnpj">CNPJ:</label>
                <input type="text" id="cnpj" name="cnpj" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" required><br>
                <label for="endereco">Endereço:</label>
                <textarea id="endereco" name="endereco" required></textarea><br>
                <button type="submit">Adicionar Empresa</button>
            </form>
            <script src="assets/js/script.js"></script>
        </div>
                       
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
