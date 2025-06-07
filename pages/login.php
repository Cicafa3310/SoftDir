<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password_hash'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['username']  = $user['username'];
        header('Location: profile.php');
        exit();
    } else {
        $error = "Неверный логин или пароль";
    }
}
include '../includes/header.php';
?>
<h2>Вход</h2>
<form method="post">
    <input name="username" placeholder="Имя пользователя" required>
    <input name="password" type="password" placeholder="Пароль" required>
    <button type="submit">Войти</button>
</form>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php include '../includes/footer.php'; ?>
