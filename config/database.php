<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'electro_store');
define('DB_USER', 'electro');
define('DB_PASS', 'Electr0St0re!2026');
define('APP_URL', 'https://abdalgani.com/electro-store');
define('DELIVERY_FEE', 500.00);
define('CURRENCY', 'ل.س');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER, DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("خطأ في الاتصال بقاعدة البيانات");
}
