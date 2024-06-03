<?php

session_start();

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST['cpf'];

    // Consultar o banco de dados para encontrar o funcionário pelo CPF
    $stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE cpf = ?');
    $stmt->execute([$cpf]);
    $funcionario = $stmt->fetch();

    if ($funcionario) {
        // Login bem-sucedido
        $_SESSION['funcionario_nome'] = $funcionario['nome'];
        $_SESSION['funcionario_id'] = $funcionario['id'];
        $_SESSION['empresa_id'] = $funcionario['empresa_id'];
        $_SESSION['logged_in'] = true;
        header('Location: registro_ponto.php');
        exit;
    } else {
        // CPF inválido
        $error = "CPF inválido!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .login-container {
      max-width: 900px;
      margin: auto;
      padding: 10% 0;
    }
    .login-box {
      display: flex;
      border: 1px solid #ddd;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .login-image {
      width: 50%;
      background: url('assets/img/banner1.png') no-repeat center center;
      background-size: cover;
    }
    .login-form {
      width: 50%;
      padding: 30px;
    }
    .login-logo {
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="login-container">
      <div class="login-box">
        <div class="login-image">
        </div>
        <div class="login-form">
          <div class="login-logo">
            <h1>Tela Usuário</h1>
          </div>
          <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
          <form action="login.php" method="POST">
            <div class="form-group">
              <label for="cpf">CPF</label>
              <input type="text" class="form-control" id="cpf" name="cpf" required><br>
            </div>
            <br><br>
            <button type="submit" class="btn btn-primary btn-block mt-4">Entrar</button>
         <br>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>