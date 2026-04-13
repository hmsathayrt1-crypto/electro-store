<?php require_once 'includes/header.php';
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name, m.name as manufacturer_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN manufacturers m ON p.manufacturer_id = m.id
    WHERE p.id = ? AND p.status = 'active'
");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) { header('Location: /'); exit; }

$images = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order");
$images->execute([$id]);
$images = $images->fetchAll();

$related = $pdo->prepare("
    SELECT p.*, (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY sort_order LIMIT 1) as main_image
    FROM products p WHERE p.category_id = ? AND p.id != ? AND p.status = 'active' LIMIT 4
");
$related->execute([$product['category_id'], $id]);
$related = $related->fetchAll();
?>

<section class="product-detail">
    <div class="product-gallery">
        <?php if(!empty($images)): ?>
        <img id="main-image" src="<?= sanitize($images[0]['image_url']) ?>" alt="<?= sanitize($product['name']) ?>">
        <div class="thumbnails">
            <?php foreach($images as $img): ?>
            <img src="<?= sanitize($img['image_url']) ?>" alt="" onclick="document.getElementById('main-image').src=this.src">
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <img src="https://placehold.co/500x500/2C3E50/FFF?text=صورة" alt="<?= sanitize($product['name']) ?>">
        <?php endif; ?>
    </div>
    <div class="product-info-detail">
        <h1><?= sanitize($product['name']) ?></h1>
        <p class="category-tag"><?= sanitize($product['category_name'] ?? '') ?> <?= $product['manufacturer_name'] ? '| ' . sanitize($product['manufacturer_name']) : '' ?></p>
        <p class="price-big"><?= formatPrice($product['price']) ?></p>
        <p class="stock <?= $product['stock'] > 0 ? 'in-stock' : 'out-stock' ?>">
            <?= $product['stock'] > 0 ? '✅ متوفر (' . $product['stock'] . ' قطعة)' : '❌ نفذ المخزون' ?>
        </p>
        <div class="description"><?= nl2br(sanitize($product['description'])) ?></div>
        <?php if($product['stock'] > 0): ?>
        <div class="add-to-cart">
            <input type="number" id="qty" value="1" min="1" max="<?= $product['stock'] ?>">
            <button class="btn btn-primary btn-lg" onclick="addToCart(<?= $product['id'] ?>, document.getElementById('qty').value)">
                🛒 أضف للسلة
            </button>
        </div>
        <?php endif; ?>
        <button class="btn btn-secondary" onclick="askBotAbout(<?= $product['id'] ?>, '<?= addslashes($product['name']) ?>')">
            💬 استفسار عبر البوت
        </button>
    </div>
</section>

<?php if(!empty($related)): ?>
<section class="related">
    <h2>منتجات مشابهة</h2>
    <div class="grid grid-4">
        <?php foreach($related as $p): ?>
        <div class="product-card">
            <a href="product.php?id=<?= $p['id'] ?>">
                <img src="<?= sanitize($p['main_image'] ?? 'https://placehold.co/300x300/2C3E50/FFF?text=صورة') ?>" alt="<?= sanitize($p['name']) ?>">
            </a>
            <div class="product-info">
                <h3><a href="product.php?id=<?= $p['id'] ?>"><?= sanitize($p['name']) ?></a></h3>
                <p class="price"><?= formatPrice($p['price']) ?></p>
                <button class="btn btn-primary" onclick="addToCart(<?= $p['id'] ?>)">🛒 أضف</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<script>
function askBotAbout(id, name) {
    document.getElementById('bot-panel').style.display = 'flex';
    document.getElementById('bot-input').value = 'أريد معرفة المزيد عن: ' + name;
    sendBotMessage();
}
</script>

<?php require_once 'includes/footer.php'; ?>
