<?php
// Incluir arquivo de configuração do banco de dados
require_once '../config/db.php';

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar os dados do formulário
$nome = $_POST['nome'];
$cnpj = $_POST['cnpj'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];

// Inserir a nova empresa no banco de dados mestre
$stmt = $pdo->prepare('INSERT INTO empresas (nome, cnpj, email, telefone, endereco) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([$nome, $cnpj, $email, $telefone, $endereco]);

// Inserir usuário padrão para a empresa
$password_hash = password_hash($cnpj, PASSWORD_DEFAULT); // Hash da senha (o CNPJ)
$stmt_user = $pdo->prepare('INSERT INTO usuarios_empresa (empresa_id, username, password_hash) VALUES ((SELECT id FROM empresas WHERE cnpj = ?), ?, ?)');
$stmt_user->execute([$cnpj, $cnpj, $password_hash]);


}

// Selecionar todas as empresas cadastradas
$stmt = $pdo->query('SELECT * FROM empresas');
$empresas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresas</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <button class="toggle-menu" onclick="toggleMenu()">☰ Menu</button>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Cadastros</a></li>
                <li><a href="#">Relatórios</a></li>
                <li><a href="#">Configurações</a></li>
            </ul>
        </div>
        <div class="content">
            <nav>
                <h2>Empresas Cadastradas</h2>
                <ul>
                    <?php foreach ($empresas as $empresa): ?>
                        <li><?= $empresa['nome'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            
            <h2>Adicionar Nova Empresa</h2>
            <form action="index.php" method="POST">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required><br>
                <label for="cnpj">CNPJ:</label>
                <input type="text" id="cnpj" name="cnpj" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" required><br>
                <label for="endereco">Endereço:</label>
                <textarea id="endereco" name="endereco" required></textarea><br>
                <button type="submit">Adicionar Empresa</button>
            </form>
            <script src="assets/js/script.js"></script>
        </div>
    </div>
</body>
</html>
