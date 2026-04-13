<?php include 'partials/header.php';
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);
if ($action === 'delete' && $id) { $pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]); header('Location: products.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name=trim($_POST['name']);$desc=trim($_POST['description']??'');$price=(float)$_POST['price'];
    $stock=(int)$_POST['stock'];$catId=(int)$_POST['category_id'];$manId=(int)($_POST['manufacturer_id']?:0)?:null;
    $tags=trim($_POST['tags']??'');$featured=(int)($_POST['featured']??0);
    if ($id) {
        $pdo->prepare("UPDATE products SET name=?,description=?,price=?,stock=?,category_id=?,manufacturer_id=?,tags=?,featured=? WHERE id=?")
            ->execute([$name,$desc,$price,$stock,$catId,$manId,$tags,$featured,$id]);
    } else {
        $pdo->prepare("INSERT INTO products (name,description,price,stock,category_id,manufacturer_id,tags,featured) VALUES (?,?,?,?,?,?,?,?)")
            ->execute([$name,$desc,$price,$stock,$catId,$manId,$tags,$featured]);
        $id = $pdo->lastInsertId();
    }
    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fname = "product_{$id}_" . time() . ".{$ext}";
        $dir = dirname(__DIR__) . '/assets/images/products/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        move_uploaded_file($_FILES['image']['tmp_name'], $dir . $fname);
        $url = APP_URL . "/assets/images/products/{$fname}";
        $pdo->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?,?)")->execute([$id, $url]);
    }
    header('Location: products.php'); exit;
}
if (($action==='edit'||$action==='view') && $id) {
    $p=$pdo->prepare("SELECT * FROM products WHERE id=?");$p->execute([$id]);$product=$p->fetch();
    $imgs=$pdo->prepare("SELECT * FROM product_images WHERE product_id=?");$imgs->execute([$id]);$images=$imgs->fetchAll();
}
$cats=$pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();
$mans=$pdo->query("SELECT * FROM manufacturers ORDER BY name")->fetchAll();
$products=$pdo->query("SELECT p.*,c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.created_at DESC")->fetchAll();
?>
<?php if($action==='add'||$action==='edit'): ?>
<div class="admin-form">
    <h2><?= $action==='add'?'➕ إضافة':'✏️ تعديل' ?> منتج</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group"><label>الاسم</label><input type="text" name="name" value="<?= sanitize($product['name']??'') ?>" required></div>
        <div class="form-group"><label>الوصف</label><textarea name="description"><?= sanitize($product['description']??'') ?></textarea></div>
        <div class="form-group"><label>السعر</label><input type="number" name="price" step="0.01" value="<?= $product['price']??'' ?>" required></div>
        <div class="form-group"><label>المخزون</label><input type="number" name="stock" value="<?= $product['stock']??0 ?>"></div>
        <div class="form-group"><label>القسم</label><select name="category_id"><?php foreach($cats as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($product['category_id']??0)==$c['id']?'selected':'' ?>><?= sanitize($c['name']) ?></option>
        <?php endforeach; ?></select></div>
        <div class="form-group"><label>الشركة</label><select name="manufacturer_id"><option value="">—</option><?php foreach($mans as $m): ?>
            <option value="<?= $m['id'] ?>" <?= ($product['manufacturer_id']??0)==$m['id']?'selected':'' ?>><?= sanitize($m['name']) ?></option>
        <?php endforeach; ?></select></div>
        <div class="form-group"><label>الهااشتاغات</label><input type="text" name="tags" value="<?= sanitize($product['tags']??'') ?>" placeholder="كلمة1, كلمة2"></div>
        <div class="form-group"><label>مميز</label><input type="checkbox" name="featured" value="1" <?= ($product['featured']??0)?'checked':'' ?>></div>
        <div class="form-group"><label>صورة</label><input type="file" name="image" accept="image/*"></div>
        <?php if(!empty($images)): foreach($images as $img): ?>
            <img src="<?= $img['image_url'] ?>" style="width:80px;height:80px;object-fit:cover;border-radius:5px;margin:5px">
        <?php endforeach; endif; ?>
        <button type="submit" class="btn btn-primary">💾 حفظ</button>
        <a href="products.php" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
<?php else: ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <h2>📦 المنتجات (<?= count($products) ?>)</h2>
    <a href="?action=add" class="btn btn-primary">➕ إضافة منتج</a>
</div>
<table class="admin-table">
    <thead><tr><th>#</th><th>الاسم</th><th>القسم</th><th>السعر</th><th>المخزون</th><th>مبيعات</th><th>إجراءات</th></tr></thead>
    <tbody>
    <?php foreach($products as $p): ?>
    <tr><td><?= $p['id'] ?></td><td><?= sanitize($p['name']) ?></td><td><?= sanitize($p['cat_name']??'') ?></td>
        <td><?= number_format($p['price']) ?></td><td><?= $p['stock'] ?></td><td><?= $p['sales_count'] ?></td>
        <td><a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">✏️</a>
            <a href="?action=delete&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">🗑️</a></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php include 'partials/footer.php'; ?>
