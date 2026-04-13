<?php include 'partials/header.php';
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);
if ($action === 'delete' && $id) { $pdo->prepare("DELETE FROM bot_knowledge WHERE id=?")->execute([$id]); header('Location: bot-knowledge.php'); exit; }
if ($action === 'toggle' && $id) { $pdo->query("UPDATE bot_knowledge SET is_active = 1 - is_active WHERE id=$id"); header('Location: bot-knowledge.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q=trim($_POST['question']);$a=trim($_POST['answer']);$kw=trim($_POST['keywords']);
    if ($id) { $pdo->prepare("UPDATE bot_knowledge SET question=?,answer=?,keywords=? WHERE id=?")->execute([$q,$a,$kw,$id]); }
    else { $pdo->prepare("INSERT INTO bot_knowledge (question,answer,keywords) VALUES (?,?,?)")->execute([$q,$a,$kw]); }
    header('Location: bot-knowledge.php'); exit;
}
if ($action==='edit'&&$id) { $kb=$pdo->prepare("SELECT * FROM bot_knowledge WHERE id=?");$kb->execute([$id]);$kb=$kb->fetch(); }
$items=$pdo->query("SELECT * FROM bot_knowledge ORDER BY id DESC")->fetchAll();
?>
<?php if($action==='add'||$action==='edit'): ?>
<div class="admin-form"><h2><?= $action==='add'?'➕':'✏️' ?> سؤال</h2>
<form method="post"><div class="form-group"><label>السؤال</label><input type="text" name="question" value="<?= sanitize($kb['question']??'') ?>" required></div>
<div class="form-group"><label>الإجابة</label><textarea name="answer" required><?= sanitize($kb['answer']??'') ?></textarea></div>
<div class="form-group"><label>الكلمات المفتاحية</label><input type="text" name="keywords" value="<?= sanitize($kb['keywords']??'') ?>" placeholder="كلمة1, كلمة2"></div>
<button type="submit" class="btn btn-primary">💾 حفظ</button><a href="bot-knowledge.php" class="btn btn-secondary">إلغاء</a></form></div>
<?php else: ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px"><h2>🤖 قاعدة المعرفة</h2><a href="?action=add" class="btn btn-primary">➕ إضافة</a></div>
<table class="admin-table"><thead><tr><th>السؤال</th><th>الكلمات</th><th>نشط</th><th>إجراءات</th></tr></thead><tbody>
<?php foreach($items as $i): ?>
<tr><td><?= sanitize(mb_substr($i['question'],0,60)) ?></td><td><?= sanitize(mb_substr($i['keywords'],0,40)) ?></td>
<td><?= $i['is_active']?'✅':'❌' ?></td>
<td><a href="?action=edit&id=<?= $i['id'] ?>" class="btn btn-sm btn-primary">✏️</a>
<a href="?action=toggle&id=<?= $i['id'] ?>" class="btn btn-sm btn-secondary"><?= $i['is_active']?'⏸':'▶' ?></a>
<a href="?action=delete&id=<?= $i['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">🗑️</a></td></tr>
<?php endforeach; ?></tbody></table><?php endif; ?>
<?php include 'partials/footer.php'; ?>
