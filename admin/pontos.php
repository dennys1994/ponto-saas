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

// Consultar o banco de dados para obter os funcionários da empresa
$stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE empresa_id = ?');
$stmt->execute([$empresa_id]);
$funcionarios = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $funcionario_id = $_POST['funcionario_id'];
    $date = $_POST['date'];
    $check_in_time = $_POST['check_in_time'];
    $interval_out_time = $_POST['interval_out_time'];
    $interval_in_time = $_POST['interval_in_time'];
    $check_out_time = $_POST['check_out_time'];
    $photo = file_get_contents($_FILES['photo']['tmp_name']);

    $check_in = "$date $check_in_time";
    $interval_out = !empty($interval_out_time) ? "$date $interval_out_time" : null;
    $interval_in = !empty($interval_in_time) ? "$date $interval_in_time" : null;
    $check_out = "$date $check_out_time";

    // Verificar se é uma edição ou um novo ponto
    if (isset($_POST['ponto_id'])) {
        $ponto_id = $_POST['ponto_id'];
        $stmt = $pdo->prepare('UPDATE pontos SET check_in = ?, interval_out = ?, interval_in = ?, check_out = ?, photo = ? WHERE id = ?');
        $stmt->execute([$check_in, $interval_out, $interval_in, $check_out, $photo, $ponto_id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO pontos (empresa_id, funcionario_id, check_in, interval_out, interval_in, check_out, photo) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$empresa_id, $funcionario_id, $check_in, $interval_out, $interval_in, $check_out, $photo]);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Pontos</title>
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
            <h2>Gerenciar Pontos</h2>
            <form action="pontos.php" method="POST" enctype="multipart/form-data">
                <label for="funcionario">Selecione o Funcionário:</label>
                <select id="funcionario" name="funcionario_id" required>
                    <?php foreach ($funcionarios as $funcionario): ?>
                        <option value="<?= $funcionario['id'] ?>"><?= $funcionario['nome'] ?></option>
                    <?php endforeach; ?>
                </select><br>
                <label for="date">Data:</label>
                <input type="date" id="date" name="date" required><br>
                <label for="check_in_time">Hora de Check-in:</label>
                <input type="time" id="check_in_time" name="check_in_time" required><br>
                <label for="interval_out_time">Hora de Saída para Intervalo:</label>
                <input type="time" id="interval_out_time" name="interval_out_time"><br>
                <label for="interval_in_time">Hora de Volta do Intervalo:</label>
                <input type="time" id="interval_in_time" name="interval_in_time"><br>
                <label for="check_out_time">Hora de Check-out:</label>
                <input type="time" id="check_out_time" name="check_out_time"><br>
                <label for="photo">Foto:</label>
                <input type="file" id="photo" name="photo" accept="image/*"><br>
                <button type="submit">Salvar Ponto</button>
            </form>
            
            <h2>Pontos Cadastrados</h2>
            <?php
            $stmt = $pdo->prepare('SELECT pontos.*, funcionarios.nome FROM pontos JOIN funcionarios ON pontos.funcionario_id = funcionarios.id WHERE funcionarios.empresa_id = ?');
            $stmt->execute([$empresa_id]);
            $pontos = $stmt->fetchAll();
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Funcionário</th>
                        <th>Entrada</th>
                        <th>Saída Intervalo</th>
                        <th>Volta Intervalo</th>
                        <th>Saída</th>
                        <th>Foto</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pontos as $ponto): ?>
                        <tr>
                            <td><?= $ponto['nome'] ?></td>
                            <td><?= $ponto['check_in'] ?></td>
                            <td><?= $ponto['interval_out'] ?></td>
                            <td><?= $ponto['interval_in'] ?></td>
                            <td><?= $ponto['check_out'] ?></td>
                            <td><img src="data:image/jpeg;base64,<?= base64_encode($ponto['photo']) ?>" width="50" height="50" /></td>
                            <td>
                                <form action="pontos.php" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="ponto_id" value="<?= $ponto['id'] ?>">
                                    <input type="hidden" name="funcionario_id" value="<?= $ponto['funcionario_id'] ?>">
                                    <input type="hidden" name="check_in" value="<?= $ponto['check_in'] ?>">
                                    <input type="hidden" name="interval_out" value="<?= $ponto['interval_out'] ?>">
                                    <input type="hidden" name="interval_in" value="<?= $ponto['interval_in'] ?>">
                                    <input type="hidden" name="check_out" value="<?= $ponto['check_out'] ?>">
                                </form>
                                <td>
                                    <a href="editar_ponto.php?id=<?= $ponto['id'] ?>">Editar</a> | 
                                    <a href="excluir_ponto.php?id=<?= $ponto['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este ponto?')">Excluir</a>
                                </td>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>

