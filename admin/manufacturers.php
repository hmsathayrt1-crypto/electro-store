<?php include 'partials/header.php';
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);
if ($action === 'delete' && $id) { $pdo->prepare("DELETE FROM manufacturers WHERE id=?")->execute([$id]); header('Location: manufacturers.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']); $desc = trim($_POST['description'] ?? '');
    if ($id) { $pdo->prepare("UPDATE manufacturers SET name=?, description=? WHERE id=?")->execute([$name,$desc,$id]); }
    else { $pdo->prepare("INSERT INTO manufacturers (name,description) VALUES (?,?)")->execute([$name,$desc]); }
    header('Location: manufacturers.php'); exit;
}
if ($action === 'edit' && $id) { $m = $pdo->prepare("SELECT * FROM manufacturers WHERE id=?"); $m->execute([$id]); $m=$m->fetch(); }
$items = $pdo->query("SELECT * FROM manufacturers ORDER BY name")->fetchAll();
?>
<?php if($action==='add'||$action==='edit'): ?>
<div class="admin-form"><h2><?= $action==='add'?'➕ إضافة':'✏️ تعديل' ?> شركة</h2>
<form method="post"><div class="form-group"><label>الاسم</label><input type="text" name="name" value="<?= sanitize($m['name']??'') ?>" required></div>
<div class="form-group"><label>الوصف</label><textarea name="description"><?= sanitize($m['description']??'') ?></textarea></div>
<button type="submit" class="btn btn-primary">💾 حفظ</button><a href="manufacturers.php" class="btn btn-secondary">إلغاء</a></form></div>
<?php else: ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px"><h2>🏭 الشركات المصنعة</h2><a href="?action=add" class="btn btn-primary">➕ إضافة</a></div>
<table class="admin-table"><thead><tr><th>#</th><th>الاسم</th><th>الوصف</th><th>إجراءات</th></tr></thead><tbody>
<?php foreach($items as $i): ?><tr><td><?=$i['id']?></td><td><?=sanitize($i['name'])?></td><td><?=sanitize(mb_substr($i['description']??'',0,50))?></td>
<td><a href="?action=edit&id=<?=$i['id']?>" class="btn btn-sm btn-primary">✏️</a> <a href="?action=delete&id=<?=$i['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">🗑️</a></td></tr><?php endforeach; ?>
</tbody></table><?php endif; ?>
<?php include 'partials/footer.php'; ?>
