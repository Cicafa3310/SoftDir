<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';
$search     = trim($_GET['search'] ?? '');
$catFilter  = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? (int)$_GET['category_id'] : null;
$sort       = ($_GET['sort'] ?? 'newest') === 'oldest' ? 'ASC' : 'DESC';
$whereClauses = [];
$params       = [];
if ($search !== '') {
    $whereClauses[] = 's.name LIKE ?';
    $params[]       = '%' . $search . '%';
}
if ($catFilter) {
    $whereClauses[] = 's.category_id = ?';
    $params[]       = $catFilter;
}
$whereSQL = '';
if (count($whereClauses) > 0) {
    $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
}
$categories = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll();
$sql = "
    SELECT 
        s.id, s.name, s.version, s.developer, s.link, s.created_at, c.name AS category
    FROM software s
    LEFT JOIN categories c ON s.category_id = c.id
    $whereSQL
    ORDER BY s.created_at $sort, s.name ASC
";
$stmt     = $pdo->prepare($sql);
$stmt->execute($params);
$software = $stmt->fetchAll();
$avgStmt = $pdo->prepare("
    SELECT AVG(rating) AS avg_rating, COUNT(*) AS cnt 
    FROM reviews 
    WHERE software_id = ?
");
?>

<h2>Справочник ПО</h2>

<form method="get" class="filter-form">
    <label>
        Поиск по названию:
        <input
            type="text"
            name="search"
            value="<?= htmlspecialchars($search, ENT_QUOTES | ENT_HTML5) ?>"
            placeholder="Введите часть названия…"
        >
    </label>

    <label>
        Категория:
        <select name="category_id">
            <option value="">— Все категории —</option>
            <?php foreach ($categories as $c): ?>
                <option
                    value="<?= $c['id'] ?>"
                    <?= $catFilter === (int)$c['id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($c['name'], ENT_QUOTES | ENT_HTML5) ?>
                </option>
            <?php endforeach; ?>
        </select>

    </label>

    <label>
        Сортировка по дате:
        <select name="sort">
            <option value="newest" <?= $sort === 'DESC' ? 'selected' : '' ?>>Сначала новые</option>
            <option value="oldest" <?= $sort === 'ASC'  ? 'selected' : '' ?>>Сначала старые</option>
        </select>
    </label>
    
    <a href="software_list.php" class="reset">Сбросить</a>
</form>

<ul class="software-list">
    <?php if (count($software) === 0): ?>
        <li>По вашему запросу ничего не найдено.</li>
    <?php else: ?>
        <?php foreach ($software as $s): ?>
            <?php
                $avgStmt->execute([$s['id']]);
                $avgData = $avgStmt->fetch();
                $avgRating = $avgData['avg_rating'] 
                    ? round($avgData['avg_rating'], 1) 
                    : null;
                $reviewsCount = $avgData['cnt'];
            ?>
            <li
            data-href="software_detail.php?id=<?= $s['id'] ?>"
            onclick="window.location = this.dataset.href"
            style="cursor: pointer;"
            >
                <span class="software-name">
                <?= htmlspecialchars($s['name'], ENT_QUOTES | ENT_HTML5) ?>
                </span>
                <?php if ($s['version']): ?>
                    (v<?= htmlspecialchars($s['version'], ENT_QUOTES | ENT_HTML5) ?>)
                <?php endif; ?>

                <?php if ($s['link']): ?>
                    — <a
                        href="<?= htmlspecialchars($s['link'], ENT_QUOTES | ENT_HTML5) ?>"
                        target="_blank"
                        class="download"
                        onclick="event.stopPropagation()"
                    >
                        Установить
                    </a>
                <?php endif; ?>

                <?php if ($s['category']): ?>
                    — <?= htmlspecialchars($s['category'], ENT_QUOTES | ENT_HTML5) ?>
                <?php endif; ?>

                <br>
                <small>
                Добавлено: <?= date('Y-m-d H:i', strtotime($s['created_at'])) ?>
                </small>

                <?php if ($avgRating !== null): ?>
                    &nbsp;|&nbsp;
                    <small>
                        Средняя оценка: <?= htmlspecialchars($avgRating, ENT_QUOTES | ENT_HTML5) ?>
                        (<?= $reviewsCount ?> <?= $reviewsCount === 1 ? 'отзыв' : 'отзывов' ?>)
                    </small>
                <?php else: ?>
                    &nbsp;|&nbsp;<small>Оценок пока нет</small>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>


<?php include __DIR__ . '/../includes/footer.php'; ?>
