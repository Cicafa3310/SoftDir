<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();   
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll();



?>

<h2>Управление категориями</h2>
<p><a href="add_category.php">+ Добавить новую категорию</a></p>

<table>
<tr><th>ID</th><th>Название</th><th>Действия</th></tr>
<?php foreach ($categories as $c): ?>
    <tr>
    <td><?= $c['id'] ?></td>
    <td><?= htmlspecialchars($c['name']) ?></td>
    <td>
        <a href="edit_category.php?id=<?= $c['id'] ?>">Изменить</a>
        |
        <a href="delete_category.php?id=<?= $c['id'] ?>"
        onclick="return confirm('Удалить категорию «<?= htmlspecialchars($c['name']) ?>»?');">
        Удалить
        </a>
    </td>
    </tr>
<?php endforeach; ?>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
