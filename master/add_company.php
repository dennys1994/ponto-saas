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

    // Redirecionar de volta para a página inicial após a inserção
    header('Location: index.php');
    exit;
}
?>
