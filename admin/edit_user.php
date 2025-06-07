<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();   
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: users_list.php'); exit;
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) {
    header('Location: users_list.php'); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $role     = $_POST['role'] === 'admin' ? 'admin' : 'user';
    
    $upd = $pdo->prepare("
    UPDATE users SET username = ?, email = ?, role = ? 
    WHERE id = ?
    ");
    $upd->execute([$username, $email, $role, $id]);
    header('Location: users_list.php'); exit;
}


?>

<h2>Редактировать пользователя</h2>
<?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
<form method="post" class="admin-form">
<label>Имя:
    <input name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
</label>
<label>Email:
    <input name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" required>
</label>
<label>Роль:
    <select name="role">
    <option value="user"  <?= $user['role']==='user'  ? 'selected' : '' ?>>User</option>
    <option value="admin" <?= $user['role']==='admin' ? 'selected' : '' ?>>Admin</option>
    </select>
</label>
<button type="submit">Сохранить</button>
<a href="users_list.php">← Назад</a>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
