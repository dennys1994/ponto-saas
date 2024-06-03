<?php
session_start();

require_once '../config/db.php';
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['funcionario_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar o registro do ponto
    $empresa_id = $_SESSION['empresa_id'];
    $funcionario_id = $_SESSION['funcionario_id'];
    $timestamp = date('Y-m-d H:i:s');

    // Determinar a ação com base no parâmetro recebido
    $action = $_POST['action'];

    // Determinar qual coluna deve ser atualizada com base na ação
    $column_to_update = '';
    switch ($action) {
        case 'entrada':
            $column_to_update = 'check_in';
            break;
        case 'intervalo_saida':
            $column_to_update = 'interval_out';
            break;
        case 'intervalo_volta':
            $column_to_update = 'interval_in';
            break;
        case 'saida':
            $column_to_update = 'check_out';
            break;
        case 'photo':
            $column_to_update = 'photo';
            break;
        default:
            // Ação desconhecida
            break;
    }

    if($column_to_update == 'photo'){
        if (!empty($_FILES['photo']['tmp_name'])) {
            // Lê o conteúdo do arquivo
            $photo = file_get_contents($_FILES['photo']['tmp_name']);
    
            // Atualiza o banco de dados com a foto
            $stmt = $pdo->prepare('UPDATE pontos SET photo = ? WHERE empresa_id = ? AND funcionario_id = ?');
            $stmt->execute([$photo, $empresa_id, $funcionario_id]);
    
            if ($stmt->rowCount() > 0) {
                echo "Foto atualizada com sucesso no banco de dados.";
            } else {
                echo "Falha ao atualizar a foto no banco de dados.";
            }
        } else {
            echo "Nenhuma foto foi selecionada para upload.";
        }
    } elseif (!empty($column_to_update)) {
        // Verificar se há um registro existente para o funcionário e empresa
        $existing_stmt = $pdo->prepare("SELECT * FROM pontos WHERE empresa_id = ? AND funcionario_id = ?");
        $existing_stmt->execute([$empresa_id, $funcionario_id]);
        $existing_row = $existing_stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existing_row) {
            // Já existe um registro, então atualize-o
            $sql = "UPDATE pontos SET $column_to_update = ? WHERE empresa_id = ? AND funcionario_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$timestamp, $empresa_id, $funcionario_id]);
    
            if ($stmt->rowCount() > 0) {
                echo "Registro de ponto atualizado com sucesso.";
            } else {
                echo "Falha ao atualizar o registro de ponto.";
            }
        } else {
            // Não existe um registro, então insira um novo
            $sql = "INSERT INTO pontos (empresa_id, funcionario_id, $column_to_update) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$empresa_id, $funcionario_id, $timestamp]);
    
            if ($stmt->rowCount() > 0) {
                echo "Registro de ponto criado com sucesso.";
            } else {
                echo "Falha ao criar o registro de ponto.";
            }
        }
    } 
    elseif ($_POST['action'] === 'apagar') {
        $ponto_id = $_POST['ponto_id'];
        $tipo_horario = $_POST['tipo_horario'];

        // Monta a query de atualização
        $sql = "UPDATE pontos SET $tipo_horario = NULL WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ponto_id]);

        if ($stmt->rowCount() > 0) {
            echo "Horário apagado com sucesso.";
        } else {
            echo "Falha ao apagar o horário.";
        }
    }
    else {
        echo "Ação desconhecida.";
    }
    
    
}
$empresa_id = $_SESSION['empresa_id'];
$funcionario_id = $_SESSION['funcionario_id'];
$timestamp = date('Y-m-d H:i:s');
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
                <a class="navbar-brand" href="index.html">
                <?php
    // Exibir saudação com o nome do funcionário
    echo "Olá, {$_SESSION['funcionario_nome']}!";
     ?>        
    </a> 
            </div>
            
  <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;">
  <span id="current-time"></span>    
  <a href="logout.php" class="btn btn-danger square-btn-adjust">Logout</a> 

</div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				<li class="text-center">
                    <img src="assets/img/find_user.png" class="user-image img-responsive"/>
					</li>
                    <li>
                        <a class="active-menu" href="#"><i class="fa fa-edit fa-3x"></i>Registro de ponto</a>
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
                     <h2>Registro de Ponto</h2>
    <?php
    // Exibir hora atual usando JavaScript
    // Obtém a data atual
    $current_date = date('Y-m-d');

// Consulta SQL para obter os pontos batidos do dia
$sql = "SELECT id, check_in, interval_out, interval_in, check_out, photo FROM pontos WHERE empresa_id = ? AND funcionario_id = ? AND DATE(check_in) = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$empresa_id, $funcionario_id, $current_date]);
$pontos_do_dia = $stmt->fetchAll();

// Tabela para exibir os pontos batidos do dia
echo "<h3>Pontos Batidos do Dia</h3>";
echo "<table border='1' class='table table-striped table-bordered table-hover'>";
echo "<tr><th>Check-in</th><th>Saída Intervalo</th><th>Volta Intervalo</th><th>Check-out</th><th>Foto</th><th>Ações</th></tr>";
foreach ($pontos_do_dia as $ponto) {
    echo "<tr>";
    echo "<td>{$ponto['check_in']}</td>";
    echo "<td>{$ponto['interval_out']}</td>";
    echo "<td>{$ponto['interval_in']}</td>";
    echo "<td>{$ponto['check_out']}</td>";
    echo "<td>";
if (array_key_exists('photo', $ponto) && $ponto['photo']) {
    // Exibe a foto se a chave "photo" estiver presente e não for vazia
    echo '<img src="data:image/jpeg;base64,' . base64_encode($ponto['photo']) . '" width="100" height="100">';
} else {
    // Exibe "Sem foto" se não houver foto associada
    echo "Sem foto";
}
echo "</td>";

    echo "<td>";
    echo "<form action='registro_ponto.php' method='POST'>";
    echo "<input type='hidden' name='action' value='apagar'>";
    echo "<input type='hidden' name='ponto_id' value='{$ponto['id']}'>"; // Adiciona o ID do ponto como um campo oculto
    echo "<select name='tipo_horario' class='form-control'>";
    echo "<option value='check_in'>Entrada</option>";
    echo "<option value='interval_out'>Saída do Intervalo</option>";
    echo "<option value='interval_in'>Volta do Intervalo</option>";
    echo "<option value='check_out'>Saída</option>";
    echo "<option value='photo'>Foto</option>";
    echo "</select>";
    echo "<button type='submit' class='btn btn-danger'>Apagar</button>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
}

echo "</table>";


echo "</table>";


    ?>
    <script>
        setInterval(function() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            document.getElementById('current-time').textContent = 'Hora atual: ' + hours + ':' + minutes + ':' + seconds;
        }, 1000);
    </script>

    <!-- Formulário de registro de ponto -->
    <form action="registro_ponto.php" method="POST">
        <input type="hidden" name="action" value="entrada"> <!-- Valor padrão é entrada -->
        <button type="submit" class="btn btn-primary">Registrar Entrada</button>
    </form>
<br>
    <form action="registro_ponto.php" method="POST">
        <input type="hidden" name="action" value="intervalo_saida">
        <button type="submit" class="btn btn-primary">Registrar Saída do Intervalo</button>
    </form>
<br>
    <form action="registro_ponto.php" method="POST">
        <input type="hidden" name="action" value="intervalo_volta">
        <button type="submit" class="btn btn-primary">Registrar Volta do Intervalo</button>
    </form>
<br>
    <form action="registro_ponto.php" method="POST">
        <input type="hidden" name="action" value="saida">
        <button type="submit" class="btn btn-primary">Registrar Saída</button>
    </form>
<br>
    <!-- Formulário de upload de foto -->
    <form action="registro_ponto.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="photo">
    <input type="hidden" name="action" value="photo" class="form-control"> <!-- Adiciona ação foto -->
    <br>
    <button type="submit" name="submit" class="btn btn-primary">Enviar Foto</button>
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