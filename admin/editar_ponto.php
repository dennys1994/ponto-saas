<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Incluir arquivo de configuração do banco de dados
require_once '../config/db.php';

// Verificar se o ID do ponto foi fornecido
if (isset($_GET['id'])) {
    $ponto_id = $_GET['id'];

    // Consultar o banco de dados para obter os dados do ponto
    $stmt = $pdo->prepare('SELECT * FROM pontos WHERE id = ?');
    $stmt->execute([$ponto_id]);
    $ponto = $stmt->fetch();

    if (!$ponto) {
        // Redirecionar de volta para a página de gerenciamento de pontos se o ponto não for encontrado
        header('Location: pontos.php');
        exit;
    }
} else {
    // Redirecionar de volta para a página de gerenciamento de pontos se o ID não for fornecido
    header('Location: pontos.php');
    exit;
}

// Processar o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $funcionario_id = $_POST['funcionario_id'];
    $date = $_POST['date'];
    $check_in_time = $_POST['check_in_time'];
    $interval_out_time = $_POST['interval_out_time'];
    $interval_in_time = $_POST['interval_in_time'];
    $check_out_time = $_POST['check_out_time'];

    $check_in = "$date $check_in_time";
    $interval_out = "$date $interval_out_time";
    $interval_in = "$date $interval_in_time";
    $check_out = "$date $check_out_time";

    // Atualizar os dados do ponto no banco de dados
    $stmt = $pdo->prepare('UPDATE pontos SET funcionario_id = ?, check_in = ?, interval_out = ?, interval_in = ?, check_out = ? WHERE id = ?');
    $stmt->execute([$funcionario_id, $check_in, $interval_out, $interval_in, $check_out, $ponto_id]);

    // Redirecionar de volta para a página de gerenciamento de pontos
    header('Location: pontos.php');
    exit;
}

// Consultar o banco de dados para obter a lista de funcionários
$stmt = $pdo->prepare('SELECT id, nome FROM funcionarios WHERE empresa_id = ?');
$stmt->execute([$ponto['empresa_id']]);
$funcionarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ponto</title>
</head>
<body>
    <h2>Editar Ponto</h2>
    <form action="editar_ponto.php?id=<?= $ponto_id ?>" method="POST">
        <label for="funcionario">Selecione o Funcionário:</label>
        <select id="funcionario" name="funcionario_id" required>
            <?php foreach ($funcionarios as $funcionario): ?>
                <option value="<?= $funcionario['id'] ?>" <?= $funcionario['id'] == $ponto['funcionario_id'] ? 'selected' : '' ?>><?= $funcionario['nome'] ?></option>
            <?php endforeach; ?>
        </select><br>
        <label for="date">Data:</label>
        <input type="date" id="date" name="date" value="<?= date('Y-m-d', strtotime($ponto['check_in'])) ?>" required><br>
        <label for="check_in_time">Hora de Entrada:</label>
        <input type="time" id="check_in_time" name="check_in_time" value="<?= date('H:i', strtotime($ponto['check_in'])) ?>" required><br>
        <label for="interval_out_time">Hora de Saída para Intervalo:</label>
        <input type="time" id="interval_out_time" name="interval_out_time" value="<?= date('H:i', strtotime($ponto['interval_out'])) ?>"><br>
        <label for="interval_in_time">Hora de Retorno do Intervalo:</label>
        <input type="time" id="interval_in_time" name="interval_in_time" value="<?= date('H:i', strtotime($ponto['interval_in'])) ?>"><br>
        <label for="check_out_time">Hora de Saída:</label>
        <input type="time" id="check_out_time" name="check_out_time" value="<?= date('H:i', strtotime($ponto['check_out'])) ?>" required><br>
        <button type="submit">Atualizar Ponto</button>
    </form>
    <a href="pontos.php">Voltar</a>
</body>
</html>
