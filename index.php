<?php require_once 'includes/header.php';

// Featured products
$featured = $pdo->query("
    SELECT p.*, c.name as category_name,
           (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY sort_order LIMIT 1) as main_image
    FROM products p LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.featured = 1 AND p.status = 'active'
    ORDER BY p.created_at DESC LIMIT 12
")->fetchAll();

// Categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();

// Latest products
$latest = $pdo->query("
    SELECT p.*, c.name as category_name,
           (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY sort_order LIMIT 1) as main_image
    FROM products p LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active'
    ORDER BY p.created_at DESC LIMIT 8
")->fetchAll();
?>

<section class="hero">
    <h1>⚡ متجر الأدوات الكهربائية والإلكترونية</h1>
    <p>أفضل المنتجات بأسعار منافسة مع توصيل سريع</p>
    <div class="search-box">
        <input type="text" id="search-input" placeholder="ابحث عن منتج..." onkeyup="searchProducts()">
        <button onclick="searchProducts()">🔍 بحث</button>
    </div>
</section>

<section class="categories">
    <h2>📂 الأقسام</h2>
    <div class="grid grid-5">
        <?php foreach($categories as $cat): ?>
        <a href="category.php?id=<?= $cat['id'] ?>" class="category-card">
            <?php if($cat['image']): ?>
                <img src="<?= sanitize($cat['image']) ?>" alt="<?= sanitize($cat['name']) ?>">
            <?php else: ?>
                <div class="cat-icon">📂</div>
            <?php endif; ?>
            <span><?= sanitize($cat['name']) ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="featured">
    <h2>⭐ منتجات مميزة</h2>
    <div class="grid grid-4">
        <?php foreach($featured as $p): ?>
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
</section>

<section class="latest">
    <h2>🆕 آخر المنتجات</h2>
    <div id="products-grid" class="grid grid-4">
        <?php foreach($latest as $p): ?>
        <div class="product-card">
            <a href="product.php?id=<?= $p['id'] ?>">
                <img src="<?= sanitize($p['main_image'] ?? 'https://placehold.co/300x300/2C3E50/FFF?text=صورة') ?>" alt="<?= sanitize($p['name']) ?>" loading="lazy">
            </a>
            <div class="product-info">
                <h3><a href="product.php?id=<?= $p['id'] ?>"><?= sanitize($p['name']) ?></a></h3>
                <p class="category-tag"><?= sanitize($p['category_name'] ?? '') ?></p>
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
</section>

<?php require_once 'includes/footer.php'; ?>
