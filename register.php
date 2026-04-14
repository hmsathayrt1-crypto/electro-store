<?php require_once 'includes/header.php';
if (isLoggedIn()) { header('Location: ' . APP_URL . '/'); exit; }
?>
<section class="auth-page">
    <div class="auth-box">
        <h1>📝 إنشاء حساب جديد</h1>
        <form id="register-form" onsubmit="return handleRegister(event)">
            <input type="text" name="name" placeholder="الاسم الكامل" required>
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <input type="tel" name="phone" placeholder="رقم الهاتف">
            <input type="password" name="password" placeholder="كلمة المرور (6 أحرف)" minlength="6" required>
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <button type="submit" class="btn btn-primary btn-lg">إنشاء حساب</button>
            <p>لديك حساب؟ <a href="login.php">سجل دخول</a></p>
        </form>
        <div id="reg-msg"></div>
    </div>
</section>
<script>
async function handleRegister(e) {
    e.preventDefault();
    const form = new FormData(e.target);
    const res = await fetch('api/auth/register.php', {method:'POST', body: form});
    const data = await res.json();
    if (data.success) { window.location.href = 'index.php'; }
    else { document.getElementById('reg-msg').innerHTML = '<p class="error">'+data.error+'</p>'; }
    return false;
}
</script>
<?php require_once 'includes/footer.php'; ?>
