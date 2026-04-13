<?php include 'partials/header.php';
$users = $pdo->query("SELECT * FROM users WHERE role='customer' ORDER BY created_at DESC")->fetchAll();
?>
<h2>👥 المستخدمين (<?= count($users) ?>)</h2>
<table class="admin-table"><thead><tr><th>#</th><th>الاسم</th><th>الإيميل</th><th>الهاتف</th><th>الرصيد</th><th>التاريخ</th></tr></thead><tbody>
<?php foreach($users as $u): ?>
<tr><td><?= $u['id'] ?></td><td><?= sanitize($u['name']) ?></td><td><?= sanitize($u['email']) ?></td>
<td><?= sanitize($u['phone']) ?></td><td><?= number_format($u['balance']) ?> ل.س</td><td><?= $u['created_at'] ?></td></tr>
<?php endforeach; ?></tbody></table>
<?php include 'partials/footer.php'; ?>
