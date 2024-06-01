<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $db_name = 'empresa_' . strtolower(preg_replace('/\s+/', '_', $nome));

    // Insere a empresa no banco de dados mestre
    $stmt = $pdo->prepare('INSERT INTO empresas (nome, cnpj, email, telefone, endereco, db_name) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$nome, $cnpj, $email, $telefone, $endereco, $db_name]);

    // Cria o banco de dados para a nova empresa
    $pdo->exec("CREATE DATABASE $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // Conecta ao novo banco de dados e cria as tabelas necessárias
    $dsn_empresa = "mysql:host=$host;dbname=$db_name;charset=$charset";
    $pdo_empresa = new PDO($dsn_empresa, $user, $pass, $options);

    // Cria a tabela de usuários (funcionários)
    $pdo_empresa->exec("
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            cpf VARCHAR(11) UNIQUE NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(15),
            address TEXT,
            photo LONGBLOB,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Cria a tabela de registros de ponto
    $pdo_empresa->exec("
        CREATE TABLE pontos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            check_in DATETIME,
            check_out DATETIME,
            photo LONGBLOB,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");

    echo "Empresa cadastrada e banco de dados criado com sucesso!";
}
?>

<form action="register_company.php" method="POST">
    <input type="text" name="nome" placeholder="Nome da Empresa" required>
    <input type="text" name="cnpj" placeholder="CNPJ" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="telefone" placeholder="Telefone" required>
    <textarea name="endereco" placeholder="Endereço" required></textarea>
    <button type="submit">Registrar Empresa</button>
</form>
