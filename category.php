<?php require_once 'includes/header.php';

$catId = (int)($_GET['id'] ?? 0);
$search = $_GET['q'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
$categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();
$manufacturers = $pdo->query("SELECT * FROM manufacturers")->fetchAll();

$where = ["p.status = 'active'"];
$params = [];

if ($catId) { $where[] = "p.category_id = ?"; $params[] = $catId; }
if ($search) { $where[] = "(p.name LIKE ? OR p.tags LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }

$orderBy = match($sort) {
    'price_low' => 'p.price ASC',
    'price_high' => 'p.price DESC',
    'best' => 'p.sales_count DESC',
    default => 'p.created_at DESC',
};

$whereClause = implode(' AND ', $where);
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name, m.name as manufacturer_name,
           (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY sort_order LIMIT 1) as main_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN manufacturers m ON p.manufacturer_id = m.id
    WHERE $whereClause ORDER BY $orderBy
");
$stmt->execute($params);
$products = $stmt->fetchAll();

$currentCat = null;
if ($catId) {
    $cs = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $cs->execute([$catId]);
    $currentCat = $cs->fetch();
}
?>

<section class="page-header">
    <h1>📂 <?= $currentCat ? sanitize($currentCat['name']) : 'جميع المنتجات' ?></h1>
</section>

<section class="shop-layout">
    <aside class="filters">
        <h3>فلترة</h3>
        <form method="get" action="">
            <?php if($catId): ?><input type="hidden" name="id" value="<?= $catId ?>"><?php endif; ?>
            <input type="text" name="q" value="<?= sanitize($search) ?>" placeholder="بحث...">
            <select name="sort">
                <option value="newest" <?= $sort==='newest'?'selected':'' ?>>الأحدث</option>
                <option value="price_low" <?= $sort==='price_low'?'selected':'' ?>>الأقل سعراً</option>
                <option value="price_high" <?= $sort==='price_high'?'selected':'' ?>>الأعلى سعراً</option>
                <option value="best" <?= $sort==='best'?'selected':'' ?>>الأكثر مبيعاً</option>
            </select>
            <button type="submit" class="btn btn-primary">تطبيق</button>
        </form>
        <div class="cat-list">
            <a href="category.php" class="<?= !$catId?'active':'' ?>">الكل</a>
            <?php foreach($categories as $c): ?>
            <a href="category.php?id=<?= $c['id'] ?>" class="<?= $catId==$c['id']?'active':'' ?>"><?= sanitize($c['name']) ?></a>
            <?php endforeach; ?>
        </div>
    </aside>
    <div class="products-area">
        <div class="grid grid-3">
            <?php foreach($products as $p): ?>
            <div class="product-card">
                <a href="product.php?id=<?= $p['id'] ?>">
                    <img src="<?= sanitize($p['main_image'] ?? 'https://placehold.co/300x300/2C3E50/FFF?text=صورة') ?>" alt="<?= sanitize($p['name']) ?>" loading="lazy">
                </a>
                <div class="product-info">
                    <h3><a href="product.php?id=<?= $p['id'] ?>"><?= sanitize($p['name']) ?></a></h3>
                    <p class="price"><?= formatPrice($p['price']) ?></p>
                    <?php if($p['stock'] > 0): ?>
                    <button class="btn btn-primary" onclick="addToCart(<?= $p['id'] ?>)">🛒 أضف للسلة</button>
                    <?php else: ?>
                    <span class="out-of-stock">نفذ المخزون</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if(empty($products)): ?>
        <p class="empty">لا توجد منتجات</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
