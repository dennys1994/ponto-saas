<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Incluir arquivo de configuração do banco de dados
require_once '../config/db.php';

// Função para buscar funcionários com base no termo de pesquisa
function buscarFuncionarios($pdo, $empresa_id, $termo)
{
    $stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE empresa_id = ? AND (nome LIKE ? OR cpf LIKE ? OR email LIKE ? OR telefone LIKE ? OR endereco LIKE ?)');
    $termoBusca = "%$termo%";
    $stmt->execute([$empresa_id, $termoBusca, $termoBusca, $termoBusca, $termoBusca, $termoBusca]);
    return $stmt->fetchAll();
}

// Obter o ID da empresa do usuário logado
$empresa_id = $_SESSION['empresa_id'];

// Inicializar variáveis de busca e mensagem de erro
$termo = '';
$erro = '';

// Processar a pesquisa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['buscar'])) {
        $termo = $_POST['termo'];
        $funcionarios = buscarFuncionarios($pdo, $empresa_id, $termo);
    } else {
        $erro = "Ação desconhecida.";
    }
} else {
    // Consultar o banco de dados para obter todos os funcionários da empresa
    $stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE empresa_id = ?');
    $stmt->execute([$empresa_id]);
    $funcionarios = $stmt->fetchAll();
}
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
            <form action="consulta_funcionario.php" method="POST">
                <label for="termo">Buscar Funcionário:</label>
                <input type="text" id="termo" name="termo" value="<?= htmlspecialchars($termo) ?>">
                <button type="submit" name="buscar">Buscar</button>
            </form>
            <?php if ($erro): ?>
                <p><?= $erro ?></p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Endereço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($funcionarios as $funcionario): ?>
                            <tr>
                                <td><?= $funcionario['id'] ?></td>
                                <td><?= $funcionario['nome'] ?></td>
                                <td><?= $funcionario['cpf'] ?></td>
                                <td><?= $funcionario['email'] ?></td>
                                <td><?= $funcionario['telefone'] ?></td>
                                <td><?= $funcionario['endereco'] ?></td>
                                <td>
                                    <a href="editar_funcionario.php?id=<?= $funcionario['id'] ?>">Editar</a> |
                                    <a href="excluir_funcionario.php?id=<?= $funcionario['id'] ?>">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
