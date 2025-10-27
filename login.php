<?php
// login.php
session_start();
require_once __DIR__ . '/config/database.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($login) || empty($senha)) {
        $error_message = 'Por favor, preencha o login e a senha.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM ferram_usuarios WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        if ($user && password_verify($senha, $user['senha'])) {
            // Login bem-sucedido
            session_regenerate_id(); // Previne session fixation
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['nivel_acesso_id'] = $user['nivel_acesso_id'];

            header('Location: index.php');
            exit();
        } else {
            $error_message = 'Login ou senha inválidos.';
        }
    }
}

// Se o usuário já está logado, redireciona para o index
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Controle de Ferramentas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="container login-container">
        <h2>Login no Sistema</h2>
        <hr>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div>
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div>
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>

</body>

</html>