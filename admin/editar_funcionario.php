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

// Obter o ID do funcionário a ser editado
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

// Processar os dados do formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    // Atualizar os dados do funcionário no banco de dados
    $stmt = $pdo->prepare('UPDATE funcionarios SET nome = ?, cpf = ?, email = ?, telefone = ?, endereco = ? WHERE id = ?');
    $stmt->execute([$nome, $cpf, $email, $telefone, $endereco, $funcionario_id]);

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
    <title>Editar Funcionário</title>
</head>
<body>
    <div class="container">
        <h2>Editar Funcionário</h2>
        <form action="editar_funcionario.php?id=<?= $funcionario_id ?>" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($funcionario['nome']) ?>" required><br>
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($funcionario['cpf']) ?>" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($funcionario['email']) ?>" required><br>
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($funcionario['telefone']) ?>" required><br>
            <label for="endereco">Endereço:</label>
            <textarea id="endereco" name="endereco" required><?= htmlspecialchars($funcionario['endereco']) ?></textarea><br>
            <button type="submit">Salvar</button>
            <a href="consulta_funcionario.php">Cancelar</a>
        </form>
    </div>
</body>
</html>
