<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Verificar se o ID do funcionário foi fornecido via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: consulta_funcionario.php');
    exit;
}

// Incluir arquivo de configuração do banco de dados
require_once '../config/db.php';

// Obter o ID do funcionário a ser excluído
$funcionario_id = $_GET['id'];

// Obter o ID da empresa do usuário logado
$empresa_id = $_SESSION['empresa_id'];

// Verificar se o funcionário pertence à empresa do usuário logado
$stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE id = ? AND empresa_id = ?');
$stmt->execute([$funcionario_id, $empresa_id]);
$funcionario = $stmt->fetch();

if (!$funcionario) {
    // O funcionário não pertence à empresa do usuário logado, redirecionar de volta para a página de consulta
    header('Location: consulta_funcionario.php');
    exit;
}

// Processar a exclusão do funcionário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Excluir o funcionário do banco de dados
    $stmt = $pdo->prepare('DELETE FROM funcionarios WHERE id = ?');
    $stmt->execute([$funcionario_id]);

    // Redirecionar de volta para a página de consulta de funcionários
    header('Location: consulta_funcionario.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Funcionário</title>
</head>
<body>
    <div class="container">
        <h2>Excluir Funcionário</h2>
        <p>Tem certeza de que deseja excluir o funcionário <?= htmlspecialchars($funcionario['nome']) ?>?</p>
        <form action="excluir_funcionario.php?id=<?= $funcionario_id ?>" method="POST">
            <button type="submit">Sim, Excluir</button>
            <a href="consulta_funcionario.php">Cancelar</a>
        </form>
    </div>
</body>
</html>
