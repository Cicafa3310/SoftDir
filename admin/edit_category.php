<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();   
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: categories_list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?")
            ->execute([$name, $id]);
        header('Location: categories_list.php');
        exit();
    } else {
        $error = "Название не может быть пустым.";
    }
}

$category = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$category->execute([$id]);
$c = $category->fetch();
if (!$c) {
    header('Location: categories_list.php');
    exit();
}


?>

<h2>Редактировать категорию</h2>
<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post">
    <label>
        Название:
        <input name="name" value="<?= htmlspecialchars($c['name']) ?>" required>
    </label>
    <button type="submit">Сохранить</button>
    <a href="categories_list.php">← Назад</a>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
