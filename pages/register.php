<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['username'], $hash, $_POST['email']]);
    header('Location: login.php');
}
include '../includes/header.php';
?>
<h2>Регистрация</h2>
<form method="post">
    <input name="username" placeholder="Имя пользователя" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Пароль" required>
    <button type="submit">Зарегистрироваться</button>
</form>
<?php include '../includes/footer.php'; ?>
