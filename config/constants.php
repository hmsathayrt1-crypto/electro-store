<?php
define('ORDER_REVIEWING', 'reviewing');
define('ORDER_CONFIRMED', 'confirmed');
define('ORDER_SHIPPING', 'shipping');
define('ORDER_DELIVERED', 'delivered');

define('PAYMENT_CASH', 'cash');
define('PAYMENT_WALLET', 'wallet');
define('PAYMENT_GATEWAY', 'gateway');

define('STATUS_MAP', [
    'reviewing' => 'قيد المراجعة',
    'confirmed' => 'تم التأكيد',
    'shipping' => 'قيد الشحن',
    'delivered' => 'تم التوصيل ✅'
]);

define('PAYMENT_MAP', [
    'cash' => '💵 نقداً عند الاستلام',
    'wallet' => '💰 المحفظة المالية',
    'gateway' => '🏦 بوابة الدفع الإلكتروني'
]);
