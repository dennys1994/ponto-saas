<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Incluir arquivo de configuração do banco de dados
require_once '../config/db.php';

// Função para buscar funcionários com base no termo de pesquisa
function buscarFuncionarios($pdo, $empresa_id, $termo)
{
    $stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE empresa_id = ? AND (nome LIKE ? OR cpf LIKE ? OR email LIKE ? OR telefone LIKE ? OR endereco LIKE ?)');
    $termoBusca = "%$termo%";
    $stmt->execute([$empresa_id, $termoBusca, $termoBusca, $termoBusca, $termoBusca, $termoBusca]);
    return $stmt->fetchAll();
}

// Obter o ID da empresa do usuário logado
$empresa_id = $_SESSION['empresa_id'];

// Inicializar variáveis de busca e mensagem de erro
$termo = '';
$erro = '';

// Processar a pesquisa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['buscar'])) {
        $termo = $_POST['termo'];
        $funcionarios = buscarFuncionarios($pdo, $empresa_id, $termo);
    } else {
        $erro = "Ação desconhecida.";
    }
} else {
    // Consultar o banco de dados para obter todos os funcionários da empresa
    $stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE empresa_id = ?');
    $stmt->execute([$empresa_id]);
    $funcionarios = $stmt->fetchAll();
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
                        <a href="dashboard.php"><i class="fa fa-edit fa-3x"></i>Dashboard</a>
                    </li>
                      <li>
                        <a  href="cadastro_funcionario.php"><i class="fa fa-desktop fa-3x"></i>Cadastro de Funcionário</a>
                    </li>
                    <li>
                        <a  class="active-menu" href="consulta_funcionario.php"><i class="fa fa-bar-chart-o fa-3x"></i>Consulta de Funcionário</a>
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
            <h2>Consulta de Funcionário</h2>
            <form action="consulta_funcionario.php" method="POST" style="padding: 20px;>
                <label for="termo">Buscar Funcionário:</label>
                <input type="text" id="termo" name="termo" value="<?= htmlspecialchars($termo) ?>">
                <button type="submit" name="buscar" class="btn btn-danger btn-sm">Buscar</button>
            </form>
            <?php if ($erro): ?>
                <p><?= $erro ?></p>
            <?php else: ?>
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                        <th>ID</th>
                                            <th>Nome</th>
                                            <th>CPF</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th>Endereço</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($funcionarios as $funcionario): ?>
                                        <tr>
                                            <td><?= $funcionario['id'] ?></td>
                                            <td><?= $funcionario['nome'] ?></td>
                                            <td><?= $funcionario['cpf'] ?></td>
                                            <td><?= $funcionario['email'] ?></td>
                                            <td><?= $funcionario['telefone'] ?></td>
                                            <td><?= $funcionario['endereco'] ?></td>
                                            <td>
                                                <a href="editar_funcionario.php?id=<?= $funcionario['id'] ?>" class="btn btn-primary"><i class="fa fa-edit "></i>  Editar</a>
                                                <a href="excluir_funcionario.php?id=<?= $funcionario['id'] ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> Excluir</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tboody>
                
                </table>
            <?php endif; ?>
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