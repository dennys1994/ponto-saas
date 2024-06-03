<?php
session_start();

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cnpj = $_POST['cnpj'];
    $password = $_POST['password'];

    // Consultar o banco de dados para obter o usuário com o CNPJ fornecido
    $stmt = $pdo->prepare('SELECT * FROM usuarios_empresa WHERE username = ?');
    $stmt->execute([$cnpj]);
    $user = $stmt->fetch();

    // Verificar se o usuário existe e a senha está correta
    if ($user && password_verify($password, $user['password_hash'])) {
        // Login bem-sucedido, armazenar o ID do usuário na sessão
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['empresa_id'] = $user['empresa_id'];
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php'); // Redirecionar para o dashboard após o login
        exit;
    } else {
        // Credenciais inválidas
        $error = "Credenciais inválidas!";
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
            <h1>Tela Administrativa</h1>
          </div>
          <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
          <form action="login.php" method="POST">
            <div class="form-group">
              <label for="email">CNPJ</label>
              <input type="text" class="form-control" id="cnpj" name="cnpj" required><br>
            </div>
            <div class="form-group">
              <label for="password">Senha</label>
              <input type="password" class="form-control" id="password" name="password" required><br>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-4">Entrar</button>
            <div class="text-center mt-3">
              <a href="#">Esqueci minha senha?</a>
            </div>
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