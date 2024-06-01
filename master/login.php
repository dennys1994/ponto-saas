<?php
session_start();

// Incluir arquivo de configuração do banco de dados
require_once '../config/db.php';

// Verificar se o usuário já está autenticado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter as credenciais do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar o banco de dados para obter o hash da senha do usuário
    $stmt = $pdo->prepare('SELECT * FROM users_master WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verificar se o usuário existe e a senha está correta
    if ($user && password_verify($password, $user['password_hash'])) {
        // Login bem-sucedido
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
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
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Login</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form action="login.php" method="POST">
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required><br>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
