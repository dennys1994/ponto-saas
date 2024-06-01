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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionário</title>
</head>
<body>
    <div class="container">
        <nav>
        <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="cadastro_funcionario.php">Cadastro de Funcionário</a></li>
                <li><a href="consulta_funcionario.php">Consulta de Funcionário</a></li>
                <li><a href="pontos.php">Gerenciar Pontos</a></li>
                <li><a href="relatorios.php">Relatórios</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <main>
            <h2>Cadastro de Funcionário</h2>
            <form action="cadastro_funcionario.php" method="POST">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required><br>
                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone"><br>
                <label for="endereco">Endereço:</label>
                <textarea id="endereco" name="endereco"></textarea><br>
                <button type="submit">Cadastrar</button>
            </form>
        </main>
    </div>
</body>
</html>
