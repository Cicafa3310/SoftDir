<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_login();                       
$username = $_SESSION['username'] ?? 'Гость';
include '../includes/header.php';
?>
<h2>Ваш профиль</h2>
<p>Добро пожаловать, <strong><?= htmlspecialchars($username) ?></strong>!</p>

<?php include '../includes/footer.php'; ?>
