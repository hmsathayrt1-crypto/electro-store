<?php include 'partials/header.php';
$logs = $pdo->query("SELECT cl.*, u.name as user_name FROM chat_logs cl LEFT JOIN users u ON cl.user_id=u.id ORDER BY cl.created_at DESC LIMIT 100")->fetchAll();
?>
<h2>💬 سجل المحادثات</h2>
<table class="admin-table"><thead><tr><th>المستخدم</th><th>الرسالة</th><th>الرد</th><th>المصدر</th><th>التاريخ</th></tr></thead><tbody>
<?php foreach($logs as $l): ?>
<tr><td><?= sanitize($l['user_name']??'زائر') ?></td><td><?= sanitize(mb_substr($l['message'],0,50)) ?></td>
<td><?= sanitize(mb_substr($l['response'],0,80)) ?></td><td><?= $l['source'] ?></td><td><?= $l['created_at'] ?></td></tr>
<?php endforeach; ?></tbody></table>
<?php include 'partials/footer.php'; ?>
