<?php
require_once __DIR__ . '/functions.php';
startSession();
$cartCount = getCartCount($pdo);
$csrf = generateCSRF();
?><!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجري الإلكتروني — أدوات كهربائية وإلكترونية</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/bot.css">
</head>
<body>
<nav class="navbar">
    <div class="container nav-content">
        <a href="<?= APP_URL ?>/" class="logo">⚡ متجري</a>
        <div class="nav-links">
            <a href="<?= APP_URL ?>/">الرئيسية</a>
            <a href="<?= APP_URL ?>/category.php">الأقسام</a>
            <?php if(isLoggedIn()): ?>
                <a href="<?= APP_URL ?>/wallet.php">المحفظة</a>
                <a href="<?= APP_URL ?>/order-tracking.php">طلباتي</a>
                <a href="<?= APP_URL ?>/profile.php">مرحباً، <?= sanitize($_SESSION['user_name']) ?></a>
                <?php if(isAdmin()): ?>
                    <a href="<?= APP_URL ?>/admin/" class="btn-admin">لوحة التحكم</a>
                <?php endif; ?>
                <a href="<?= APP_URL ?>/logout.php">خروج</a>
            <?php else: ?>
                <a href="<?= APP_URL ?>/login.php">دخول</a>
                <a href="<?= APP_URL ?>/register.php">تسجيل</a>
            <?php endif; ?>
        </div>
        <a href="<?= APP_URL ?>/cart.php" class="cart-btn">🛒 <span id="cart-count"><?= $cartCount ?></span></a>
        <button class="mobile-menu-btn" onclick="document.querySelector('.nav-links').classList.toggle('show')">☰</button>
    </div>
</nav>
<main class="container">
