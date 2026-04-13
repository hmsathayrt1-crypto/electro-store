<?php require_once 'includes/header.php';
if (isLoggedIn()) { header('Location: ' . APP_URL . '/'); exit; }
?>
<section class="auth-page">
    <div class="auth-box">
        <h1>🔐 تسجيل الدخول</h1>
        <form id="login-form" onsubmit="return handleLogin(event)">
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <button type="submit" class="btn btn-primary btn-lg">دخول</button>
            <p>ليس لديك حساب؟ <a href="register.php">سجل الآن</a></p>
        </form>
        <div id="login-msg"></div>
    </div>
</section>
<script>
async function handleLogin(e) {
    e.preventDefault();
    const form = new FormData(e.target);
    const res = await fetch('api/auth/login.php', {method:'POST', body: form});
    const data = await res.json();
    if (data.success) { window.location.href = data.role === 'admin' ? 'admin/' : '/'; }
    else { document.getElementById('login-msg').innerHTML = '<p class="error">'+data.error+'</p>'; }
    return false;
}
</script>
<?php require_once 'includes/footer.php'; ?>
