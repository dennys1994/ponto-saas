<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Incluir arquivo de configuração do banco de dados
require_once '../config/db.php';

// Código para consultar e editar informações dos funcionários aqui

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Funcionário</title>
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
            <h2>Consulta de Funcionário</h2>
            <!-- Formulário ou tabela para consultar e editar informações dos funcionários -->
        </main>
    </div>
</body>
</html>
