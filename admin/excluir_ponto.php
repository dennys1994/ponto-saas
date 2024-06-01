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

    // Deletar o ponto do banco de dados
    $stmt = $pdo->prepare('DELETE FROM pontos WHERE id = ?');
    $stmt->execute([$ponto_id]);

    // Redirecionar de volta para a página de gerenciamento de pontos
    header('Location: pontos.php');
    exit;
} else {
    // Redirecionar de volta para a página de gerenciamento de pontos se o ID não for fornecido
    header('Location: pontos.php');
    exit;
}
?>
