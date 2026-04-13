<?php require_once 'includes/header.php';
requireLogin();

$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$_SESSION['user_id']]);
$user = $user->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $stmt = $pdo->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
    $stmt->execute([$name, $phone, $address, $_SESSION['user_id']]);
    $_SESSION['user_name'] = $name;
    header('Location: profile.php?saved=1');
    exit;
}
?>
<section class="page-header"><h1>👤 الملف الشخصي</h1></section>
<section class="profile-page">
    <?php if(isset($_GET['saved'])): ?><p class="success">✅ تم الحفظ</p><?php endif; ?>
    <form method="post">
        <div class="form-group"><label>الاسم</label><input type="text" name="name" value="<?= sanitize($user['name']) ?>" required></div>
        <div class="form-group"><label>البريد</label><input type="email" value="<?= sanitize($user['email']) ?>" disabled></div>
        <div class="form-group"><label>الهاتف</label><input type="tel" name="phone" value="<?= sanitize($user['phone']) ?>"></div>
        <div class="form-group"><label>العنوان</label><textarea name="address"><?= sanitize($user['address']) ?></textarea></div>
        <button type="submit" class="btn btn-primary">💾 حفظ التعديلات</button>
    </form>
</section>
<?php require_once 'includes/footer.php'; ?>
