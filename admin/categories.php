<?php include 'partials/header.php';
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id) {
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    header('Location: categories.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $sort = (int)($_POST['sort_order'] ?? 0);
    if ($id) {
        $pdo->prepare("UPDATE categories SET name=?, description=?, sort_order=? WHERE id=?")->execute([$name, $desc, $sort, $id]);
    } else {
        $pdo->prepare("INSERT INTO categories (name, description, sort_order) VALUES (?,?,?)")->execute([$name, $desc, $sort]);
    }
    header('Location: categories.php'); exit;
}
if ($action === 'edit' && $id) {
    $cat = $pdo->prepare("SELECT * FROM categories WHERE id=?"); $cat->execute([$id]); $cat = $cat->fetch();
}
$cats = $pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();
?>

<?php if($action === 'add' || $action === 'edit'): ?>
<div class="admin-form">
    <h2><?= $action === 'add' ? '➕ إضافة قسم' : '✏️ تعديل قسم' ?></h2>
    <form method="post">
        <div class="form-group"><label>الاسم</label><input type="text" name="name" value="<?= sanitize($cat['name'] ?? '') ?>" required></div>
        <div class="form-group"><label>الوصف</label><textarea name="description"><?= sanitize($cat['description'] ?? '') ?></textarea></div>
        <div class="form-group"><label>الترتيب</label><input type="number" name="sort_order" value="<?= $cat['sort_order'] ?? 0 ?>"></div>
        <button type="submit" class="btn btn-primary">💾 حفظ</button>
        <a href="categories.php" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
<?php else: ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <h2>📂 الأقسام</h2>
    <a href="?action=add" class="btn btn-primary">➕ إضافة قسم</a>
</div>
<table class="admin-table">
    <thead><tr><th>#</th><th>الاسم</th><th>الوصف</th><th>الترتيب</th><th>إجراءات</th></tr></thead>
    <tbody>
    <?php foreach($cats as $c): ?>
    <tr>
        <td><?= $c['id'] ?></td>
        <td><?= sanitize($c['name']) ?></td>
        <td><?= sanitize(mb_substr($c['description'] ?? '', 0, 50)) ?></td>
        <td><?= $c['sort_order'] ?></td>
        <td>
            <a href="?action=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-primary">✏️</a>
            <a href="?action=delete&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">🗑️</a>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php include 'partials/footer.php'; ?>
