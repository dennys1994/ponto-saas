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

// Consultar o banco de dados para obter um resumo dos funcionários e das últimas atividades de ponto
$stmt = $pdo->prepare('SELECT COUNT(*) AS total_funcionarios FROM funcionarios WHERE empresa_id = ?');
$stmt->execute([$empresa_id]);
$total_funcionarios = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT * FROM pontos WHERE empresa_id = ? ORDER BY check_in DESC LIMIT 5');
$stmt->execute([$empresa_id]);
$ultimas_atividades = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT funcionarios.nome AS nome_funcionario, pontos.check_in, pontos.check_out FROM pontos INNER JOIN funcionarios ON pontos.funcionario_id = funcionarios.id WHERE pontos.empresa_id = ? ORDER BY pontos.check_in DESC LIMIT 5');
$stmt->execute([$empresa_id]);
$ultimas_atividades = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            <h2>Dashboard</h2>
            <div>
                <h3>Resumo</h3>
                <p>Total de Funcionários: <?= $total_funcionarios ?></p>
            </div>
            <div>
                <h3>Últimas Atividades de Ponto</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Funcionário</th>
                            <th>Entrada</th>
                            <th>Saída</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimas_atividades as $atividade): ?>
                            <tr>
                                <td><?= $atividade['nome_funcionario'] ?></td>
                                <td><?= $atividade['check_in'] ?></td>
                                <td><?= $atividade['check_out'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
