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
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form action="login.php" method="POST">
    <label for="cnpj">CNPJ:</label>
    <input type="text" id="cnpj" name="cnpj" required><br>
    <label for="password">Senha:</label>
    <input type="password" id="password" name="password" required><br>
    <button type="submit">Login</button>
</form>

</body>
</html>
