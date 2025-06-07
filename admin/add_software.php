<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $version     = trim($_POST['version']);
    $developer   = trim($_POST['developer']);
    $description = trim($_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $link        = trim($_POST['link']);

    if ($name === '') {
        $error = 'Название программы не может быть пустым.';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO software 
            (name, version, developer, description, category_id, link) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $name,
            $version,
            $developer,
            $description,
            $category_id ?: null,
            $link ?: null
        ]);
        header('Location: ../pages/software_list.php');
        exit();
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<h2>Добавить программу</h2>

<?php if ($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">
<label>
    Название:
    <input
    name="name"
    required
    value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
    >
</label>

<label>
    Версия:
    <input
    name="version"
    value="<?= htmlspecialchars($_POST['version'] ?? '') ?>"
    >
</label>

<label>
    Разработчик:
    <input
    name="developer"
    value="<?= htmlspecialchars($_POST['developer'] ?? '') ?>"
    >
</label>

<label>
    Описание:
    <textarea name="description" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
</label>

<label>
    Категория:
    <select name="category_id">
    <option value="">— без категории —</option>
    <?php foreach ($categories as $c): ?>
        <option
        value="<?= $c['id'] ?>"
        <?= (isset($_POST['category_id']) && $_POST['category_id'] == $c['id']) ? 'selected' : '' ?>
        >
        <?= htmlspecialchars($c['name']) ?>
        </option>
    <?php endforeach; ?>
    </select>
</label>

<label>
    Ссылка:
    <input
    name="link"
    type="url"
    placeholder="https://example.com"
    value="<?= htmlspecialchars($_POST['link'] ?? '') ?>"
    >
</label>

<button type="submit">Добавить</button>
<a href="../admin/index.php">← Вернуться к списку</a>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
