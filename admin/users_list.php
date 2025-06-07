<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();   
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';

$users = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY id")->fetchAll();
?>

<h2>Управление пользователями</h2>
<table class="admin-table">
<thead>
    <tr>
    <th>ID</th><th>Имя</th><th>Email</th><th>Роль</th><th>Дата регистрации</th><th>Действия</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($users as $u): ?>
    <tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['username']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= htmlspecialchars($u['role']) ?></td>
    <td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
    <td>
        <a href="edit_user.php?id=<?= $u['id'] ?>">Изменить</a>
        |
        <a href="delete_user.php?id=<?= $u['id'] ?>"
        onclick="return confirm('Удалить пользователя <?= htmlspecialchars($u['username']) ?>?');">
        Удалить
        </a>
    </td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
