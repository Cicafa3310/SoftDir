<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    try {
        $pdo->beginTransaction();

        
        $pdo->prepare("DELETE FROM reviews WHERE software_id = ?")
            ->execute([$id]);

        
        $pdo->prepare("DELETE FROM software WHERE id = ?")
            ->execute([$id]);

        $pdo->commit();
        
        header('Location: ../pages/software_list.php');
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Ошибка удаления: " . $e->getMessage());
    }
}


if (!$id) {
    $all = $pdo->query("SELECT id, name, version FROM software ORDER BY name")->fetchAll();
    echo '<h2>Выберите программу для редактирования</h2><ul>';
    foreach ($all as $s) {
        printf(
            '<li>%s (%s) — <a href="?id=%d">Редактировать</a></li>',
            htmlspecialchars($s['name']),
            htmlspecialchars($s['version']),
            $s['id']
        );
    }
    echo '</ul>';
    
    include __DIR__ . '/../includes/footer.php';
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $stmt = $pdo->prepare("
        UPDATE software SET
        name = ?, version = ?, developer = ?, 
        description = ?, category_id = ?, link = ?
        WHERE id = ?
    ");
    $stmt->execute([
        trim($_POST['name']),
        trim($_POST['version']),
        trim($_POST['developer']),
        trim($_POST['description']),
        (int)$_POST['category_id'] ?: null,
        trim($_POST['link']) ?: null,
        $id
    ]);
    header('Location: ../pages/software_detail.php?id=' . $id);
    exit();
}


$stmt     = $pdo->prepare("SELECT * FROM software WHERE id = ?");
$stmt->execute([$id]);
$software = $stmt->fetch();
if (!$software) {
    include __DIR__ . '/../includes/footer.php';
    header('Location: ../pages/software_list.php');
    exit();
}


$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    
    $pdo->beginTransaction();
    try {
        $pdo->prepare("DELETE FROM reviews WHERE software_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM software WHERE id = ?")->execute([$id]);
        $pdo->commit();
        header('Location: ../pages/software_list.php');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Ошибка удаления: " . $e->getMessage());
    }
}
?>

<h2>Редактировать «<?= htmlspecialchars($software['name']) ?>»</h2>

<form method="post">
<label>
    Название:
    <input name="name" required value="<?= htmlspecialchars($software['name']) ?>">
</label>
<label>
    Версия:
    <input name="version" value="<?= htmlspecialchars($software['version']) ?>">
</label>
<label>
    Разработчик:
    <input name="developer" value="<?= htmlspecialchars($software['developer']) ?>">
</label>
<label>
    Описание:
    <textarea name="description"><?= htmlspecialchars($software['description']) ?></textarea>
</label>
<label>
    Категория:
    <select name="category_id">
    <option value="">— без категории —</option>
    <?php foreach ($categories as $c): ?>
        <option value="<?= $c['id'] ?>"
        <?= $c['id'] == $software['category_id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['name']) ?>
        </option>
    <?php endforeach; ?>
    </select>
</label>
<label>
    Ссылка:
    <input name="link" type="url" value="<?= htmlspecialchars($software['link']) ?>">
</label>
    <button type="submit">Сохранить изменения</button>
    <a href="?">← Назад к списку</a>
    <form 
        method="post" 
        onsubmit="return confirm('УДАЛИТЬ ПРОГРАММУ И ВСЕ ОТЗЫВЫ НАВСЕГДА?')"
    >
        <input type="hidden" name="delete" value="1">
        <button type="submit">⚠️ Удалить программу</button>
    </form>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
