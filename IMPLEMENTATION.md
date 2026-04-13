# خطة التنفيذ الكاملة — متجر الأدوات الكهربائية والإلكترونية

> **التقنيات:** PHP + MySQL + HTML5/CSS3/JavaScript + AJAX  
> **النشر:** GitHub + استضافة PHP تدعم MySQL  
> **التاريخ:** 2026-04-13

---

## 📋 جدول المحتويات

1. [هيكل المشروع](#1-هيكل-المشروع)
2. [قاعدة البيانات — التصميم الكامل](#2-قاعدة-البيانات)
3. [المرحلة 0 — البنية التحتية](#3-المرحلة-0)
4. [المرحلة 1 — المصادقة والعرض](#4-المرحلة-1)
5. [المرحلة 2 — السلة والشراء](#5-المرحلة-2)
6. [المرحلة 3 — المحفظة والطلبات](#6-المرحلة-3)
7. [المرحلة 4 — البوت الذكي](#7-المرحلة-4)
8. [المرحلة 5 — لوحة تحكم المدير](#8-المرحلة-5)
9. [المرحلة 6 — النشر والأمان](#9-المرحلة-6)
10. [الجدول الزمني](#10-الجدول-الزمني)

---

## 1. هيكل المشروع

```
electro-store/
│
├── index.php                      # الصفحة الرئيسية
├── product.php                    # صفحة المنتج
├── category.php                   # صفحة القسم
├── cart.php                       # سلة المشتريات
├── checkout.php                   # صفحة الدفع
├── order-tracking.php             # تتبع الطلب
├── profile.php                    # الملف الشخصي
├── wallet.php                     # المحفظة الإلكترونية
├── login.php                      # تسجيل الدخول
├── register.php                   # إنشاء حساب
├── logout.php                     # تسجيل الخروج
│
├── admin/
│   ├── index.php                  # لوحة المعلومات
│   ├── categories.php             # إدارة الأقسام
│   ├── manufacturers.php          # إدارة الشركات
│   ├── products.php               # إدارة المنتجات
│   ├── orders.php                 # إدارة الطلبات
│   ├── users.php                  # إدارة المستخدمين
│   ├── wallets.php                # إدارة المحافظ
│   ├── bot-knowledge.php          # قاعدة معرفة البوت
│   ├── chat-logs.php              # سجل المحادثات
│   ├── reports.php                # التقارير
│   └── login.php                  # دخول المدير
│
├── api/
│   ├── auth/
│   │   ├── login.php              # API تسجيل الدخول
│   │   ├── register.php           # API إنشاء حساب
│   │   └── session.php            # التحقق من الجلسة
│   ├── products/
│   │   ├── list.php               # جلب المنتجات (AJAX)
│   │   ├── detail.php             # تفاصيل منتج
│   │   ├── search.php             # البحث والفلترة
│   │   └── featured.php           # المنتجات المميزة
│   ├── cart/
│   │   ├── add.php                # إضافة للسلة
│   │   ├── update.php             # تعديل الكمية
│   │   ├── remove.php             # حذف منتج
│   │   └── get.php                # جلب محتويات السلة
│   ├── orders/
│   │   ├── create.php             # إنشاء طلب
│   │   ├── track.php              # تتبع طلب
│   │   ├── list.php               # قائمة طلبات العميل
│   │   └── update-status.php      # تحديث حالة (مدير)
│   ├── wallet/
│   │   ├── balance.php            # عرض الرصيد
│   │   ├── charge.php             # شحن المحفظة
│   │   ├── pay.php                # الدفع من المحفظة
│   │   ├── deposit.php            # إيداع (مدير)
│   │   └── transactions.php       # سجل الحركات
│   ├── bot/
│   │   ├── chat.php               # إرسال رسالة للبوت
│   │   ├── order-status.php       # استعلام حالة طلب
│   │   └── product-info.php       # استفسار عن منتج
│   └── admin/
│       ├── categories.php         # CRUD أقسام
│       ├── manufacturers.php      # CRUD شركات
│       ├── products.php           # CRUD منتجات
│       ├── orders.php             # إدارة طلبات
│       ├── users.php              # إدارة مستخدمين
│       ├── wallets.php            # إدارة محافظ
│       ├── bot-knowledge.php      # CRUD قاعدة معرفة
│       ├── chat-logs.php          # جلب سجل المحادثات
│       └── reports.php            # بيانات التقارير
│
├── config/
│   ├── database.php               # إعدادات قاعدة البيانات
│   ├── app.php                    # إعدادات التطبيق
│   └── constants.php              # الثوابت (حالات الطلب، أنواع الدفع...)
│
├── includes/
│   ├── header.php                 # رأس الصفحة (Navbar)
│   ├── footer.php                 # ذيل الصفحة (Footer + Bot Widget)
│   ├── auth-guard.php             # حماية صفحات العملاء
│   ├── admin-guard.php            # حماية صفحات المدير
│   ├── db.php                     # اتصال PDO بقاعدة البيانات
│   ├── functions.php              # دوال مساعدة عامة
│   └── bot-widget.php             # ويدجت البوت العائم
│
├── assets/
│   ├── css/
│   │   ├── style.css              # التنسيق الرئيسي
│   │   ├── responsive.css         # تصميم متجاوب
│   │   ├── bot.css                # تنسيق البوت
│   │   └── admin.css              # تنسيق لوحة التحكم
│   ├── js/
│   │   ├── main.js                # JavaScript رئيسي
│   │   ├── cart.js                # AJAX السلة
│   │   ├── search.js              # AJAX البحث والفلترة
│   │   ├── checkout.js            # AJAX الشراء
│   │   ├── bot.js                 # AJAX البوت
│   │   └── admin.js               # JavaScript لوحة التحكم
│   └── images/
│       ├── products/              # صور المنتجات
│       ├── categories/            # أيقونات الأقسام
│       └── manufacturers/         # شعارات الشركات
│
├── sql/
│   ├── schema.sql                 # إنشاء الجداول
│   └── seed.sql                   # بيانات تجريبية
│
├── .gitignore
├── .env.example                   # مثال لمتغيرات البيئة
└── README.md
```

---

## 2. قاعدة البيانات

### 2.1 مخطط ERD

```
users ──< cart_items
users ──< orders ──< order_items >── products
users ──< wallet_transactions
users ──< chat_logs

categories ──< products >── manufacturers
products ──< product_images
products ──< cart_items

bot_knowledge (مستقل)
```

### 2.2 الجداول التفصيلية

#### `users` — المستخدمين
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,        -- bcrypt
    phone VARCHAR(20),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    balance DECIMAL(10,2) DEFAULT 0.00,    -- رصيد المحفظة
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `categories` — الأقسام
```sql
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `manufacturers` — الشركات المصنعة
```sql
CREATE TABLE manufacturers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `products` — المنتجات
```sql
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    category_id INT,
    manufacturer_id INT,
    tags VARCHAR(500),                     -- هاشتاغات مفصولة بفاصلة
    featured TINYINT(1) DEFAULT 0,         -- منتج مميز
    sales_count INT DEFAULT 0,             -- عدد المبيعات
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (manufacturer_id) REFERENCES manufacturers(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_manufacturer (manufacturer_id),
    INDEX idx_featured (featured),
    INDEX idx_price (price),
    INDEX idx_sales (sales_count)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `product_images` — صور المنتجات
```sql
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `cart_items` — عناصر السلة
```sql
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uk_user_product (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `orders` — الطلبات
```sql
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) UNIQUE NOT NULL,  -- رقم فريد مثل ORD-20260413-001
    user_id INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) DEFAULT 0.00,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('reviewing', 'confirmed', 'shipping', 'delivered') DEFAULT 'reviewing',
    payment_method ENUM('cash', 'wallet', 'gateway') NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    address TEXT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_order_number (order_number),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `order_items` — عناصر الطلب
```sql
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,       -- اسم المنتج وقت الشراء
    quantity INT NOT NULL,
    price_at_purchase DECIMAL(10,2) NOT NULL,  -- السعر المثبت
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `wallet_transactions` — حركات المحفظة
```sql
CREATE TABLE wallet_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    type ENUM('charge', 'purchase', 'refund', 'deposit') NOT NULL,
    description VARCHAR(255),
    reference_id INT,                         -- رقم الطلب (لو مرتبط)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user (user_id),
    INDEX idx_type (type),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `bot_knowledge` — قاعدة معرفة البوت
```sql
CREATE TABLE bot_knowledge (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    keywords VARCHAR(500) NOT NULL,           -- كلمات مفتاحية مفصولة بفاصلة
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `chat_logs` — سجل المحادثات
```sql
CREATE TABLE chat_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,                              -- NULL للزوار
    session_id VARCHAR(100),                  -- معرف جلسة الزائر
    message TEXT NOT NULL,
    response TEXT NOT NULL,
    source ENUM('knowledge_base', 'product_info', 'order_status', 'default') DEFAULT 'default',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_session (session_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 3. المرحلة 0 — البنية التحتية

### 3.1 إعداد المستودع

```bash
# إنشاء المستودع
mkdir electro-store && cd electro-store
git init
git remote add origin https://github.com/USERNAME/electro-store.git

# إنشاء المجلدات
mkdir -p api/{auth,products,cart,orders,wallet,bot,admin}
mkdir -p config includes assets/{css,js,images/{products,categories,manufacturers}} admin sql

# إنشاء الملفات الأساسية
touch .gitignore .env.example README.md
```

### 3.2 ملف .gitignore

```gitignore
.env
vendor/
node_modules/
assets/images/products/*
!assets/images/products/.gitkeep
*.log
.DS_Store
```

### 3.3 ملف .env.example

```env
DB_HOST=localhost
DB_NAME=electro_store
DB_USER=root
DB_PASS=
APP_URL=http://localhost/electro-store
DELIVERY_FEE=5.00
CURRENCY=SYP
```

### 3.4 ملف config/database.php

```php
<?php
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'electro_store';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
```

### 3.5 ملف includes/db.php

```php
<?php
require_once __DIR__ . '/../config/database.php';
// $pdo متاح الآن لكل الصفحات
```

### 3.6 إنشاء قاعدة البيانات

```bash
mysql -u root -p < sql/schema.sql
mysql -u root -p electro_store < sql/seed.sql
```

---

## 4. المرحلة 1 — المصادقة وعرض المنتجات

### 4.1 نظام المصادقة

#### includes/functions.php — الدوال المساعدة

```php
<?php
// بدء الجلسة
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_secure' => isset($_SERVER['HTTPS']),
            'use_strict_mode' => true,
        ]);
    }
}

// تسجيل دخول
function loginUser($userId, $userName, $role) {
    startSession();
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_name'] = $userName;
    $_SESSION['user_role'] = $role;
}

// التحقق من تسجيل الدخول
function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']);
}

// التحقق من صلاحية المدير
function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

// توجيه إذا لم يسجل دخول
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

// توجيه إذا ليس مدير
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /admin/login.php');
        exit;
    }
}

// تشفير كلمة المرور
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// تحقق من كلمة المرور
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// توليد رقم طلب فريد
function generateOrderNumber() {
    $date = date('Ymd');
    $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    return "ORD-{$date}-{$random}";
}

// JSON response
function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
```

#### api/auth/register.php

```php
<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$phone = trim($_POST['phone'] ?? '');

// التحقق من المدخلات
if (empty($name) || empty($email) || empty($password)) {
    jsonResponse(['error' => 'جميع الحقول مطلوبة'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(['error' => 'البريد الإلكتروني غير صالح'], 400);
}

if (strlen($password) < 6) {
    jsonResponse(['error' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل'], 400);
}

// التحقق من عدم وجود الإيميل
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    jsonResponse(['error' => 'البريد الإلكتروني مسجل مسبقاً'], 409);
}

// إنشاء الحساب
$hashed = hashPassword($password);
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $hashed, $phone]);

loginUser($pdo->lastInsertId(), $name, 'customer');
jsonResponse(['success' => true, 'message' => 'تم إنشاء الحساب بنجاح']);
```

#### api/auth/login.php

```php
<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !verifyPassword($password, $user['password'])) {
    jsonResponse(['error' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'], 401);
}

loginUser($user['id'], $user['name'], $user['role']);
jsonResponse(['success' => true, 'role' => $user['role']]);
```

### 4.2 عرض المنتجات

#### index.php — الصفحة الرئيسية

```php
<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
startSession();

// المنتجات المميزة
$featured = $pdo->query("
    SELECT p.*, c.name as category_name, m.name as manufacturer_name,
           (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY sort_order LIMIT 1) as main_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN manufacturers m ON p.manufacturer_id = m.id
    WHERE p.featured = 1 AND p.status = 'active'
    ORDER BY p.created_at DESC
    LIMIT 12
")->fetchAll();

// الأقسام
$categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();
?>
<!-- HTML الصفحة الرئيسية -->
```

#### api/products/search.php — البحث والفلترة (AJAX)

```php
<?php
require_once __DIR__ . '/../../includes/db.php';

$search = $_GET['q'] ?? '';
$categoryId = $_GET['category'] ?? '';
$manufacturerId = $_GET['manufacturer'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

$where = ["p.status = 'active'"];
$params = [];

if ($search) {
    $where[] = "(p.name LIKE ? OR p.description LIKE ? OR p.tags LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($categoryId) {
    $where[] = "p.category_id = ?";
    $params[] = $categoryId;
}
if ($manufacturerId) {
    $where[] = "p.manufacturer_id = ?";
    $params[] = $manufacturerId;
}
if ($minPrice !== '') {
    $where[] = "p.price >= ?";
    $params[] = $minPrice;
}
if ($maxPrice !== '') {
    $where[] = "p.price <= ?";
    $params[] = $maxPrice;
}

$whereClause = implode(' AND ', $where);

$orderBy = match($sort) {
    'price_low' => 'p.price ASC',
    'price_high' => 'p.price DESC',
    'best_selling' => 'p.sales_count DESC',
    default => 'p.created_at DESC',
};

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM products p WHERE $whereClause");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name,
           (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY sort_order LIMIT 1) as main_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE $whereClause
    ORDER BY $orderBy
    LIMIT $limit OFFSET $offset
");
$stmt->execute($params);
$products = $stmt->fetchAll();

jsonResponse([
    'products' => $products,
    'total' => (int)$total,
    'page' => $page,
    'pages' => ceil($total / $limit)
]);
```

#### product.php — صفحة المنتج

```php
<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
startSession();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name, m.name as manufacturer_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN manufacturers m ON p.manufacturer_id = m.id
    WHERE p.id = ? AND p.status = 'active'
");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: /index.php');
    exit;
}

$images = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order");
$images->execute([$id]);
$images = $images->fetchAll();
?>
<!-- HTML صفحة المنتج مع معرض الصور + وصف + سعر + زر إضافة للسلة + زر استفسار البوت -->
```

---

## 5. المرحلة 2 — السلة والشراء

### 5.1 إدارة السلة (AJAX)

#### assets/js/cart.js

```javascript
class Cart {
    constructor() {
        this.items = this.loadFromStorage();
        this.updateUI();
    }

    loadFromStorage() {
        const saved = localStorage.getItem('cart_items');
        return saved ? JSON.parse(saved) : [];
    }

    save() {
        localStorage.setItem('cart_items', JSON.stringify(this.items));
        // لو مسجل دخول — أرسل للسيرفر
        if (isLoggedIn) {
            this.syncToServer();
        }
    }

    async add(productId, quantity = 1) {
        const existing = this.items.find(i => i.product_id === productId);
        if (existing) {
            existing.quantity += quantity;
        } else {
            this.items.push({ product_id: productId, quantity });
        }
        this.save();
        this.updateUI();
        this.showNotification('تمت الإضافة للسلة ✓');
    }

    async syncToServer() {
        await fetch('/api/cart/sync.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ items: this.items })
        });
    }

    updateUI() {
        const count = this.items.reduce((sum, i) => sum + i.quantity, 0);
        document.getElementById('cart-count').textContent = count;
    }

    showNotification(msg) {
        // Toast notification
    }
}

const cart = new Cart();
```

#### api/cart/add.php

```php
<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
startSession();

$data = json_decode(file_get_contents('php://input'), true);
$productId = (int)($data['product_id'] ?? 0);
$quantity = max(1, (int)($data['quantity'] ?? 1));

// التحقق من المنتج
$stmt = $pdo->prepare("SELECT id, stock, price FROM products WHERE id = ? AND status = 'active'");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) jsonResponse(['error' => 'المنتج غير موجود'], 404);
if ($product['stock'] < $quantity) jsonResponse(['error' => 'الكمية المطلوبة غير متوفرة'], 400);

if (isLoggedIn()) {
    // حفظ في DB
    $stmt = $pdo->prepare("
        INSERT INTO cart_items (user_id, product_id, quantity)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + ?
    ");
    $stmt->execute([$_SESSION['user_id'], $productId, $quantity, $quantity]);
}

jsonResponse(['success' => true, 'message' => 'تمت الإضافة للسلة']);
```

### 5.2 عملية الشراء

#### checkout.php

```php
<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
requireLogin();

// جلب عناصر السلة
$stmt = $pdo->prepare("
    SELECT ci.*, p.name, p.price, p.stock,
           (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY sort_order LIMIT 1) as image
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll();

if (empty($cartItems)) {
    header('Location: /cart.php');
    exit;
}

$deliveryFee = (float)(getenv('DELIVERY_FEE') ?: 5.00);
$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
$total = $subtotal + $deliveryFee;

// جلب رصيد المحفظة
$wallet = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$wallet->execute([$_SESSION['user_id']]);
$balance = (float)$wallet->fetchColumn();
?>
<!-- HTML صفحة الدفع: اختيار العنوان + طريقة الدفع + ملخص الطلب -->
```

#### api/orders/create.php

```php
<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$data = json_decode(file_get_contents('php://input'), true);
$address = trim($data['address'] ?? '');
$paymentMethod = $data['payment_method'] ?? '';

if (!in_array($paymentMethod, ['cash', 'wallet', 'gateway'])) {
    jsonResponse(['error' => 'طريقة الدفع غير صالحة'], 400);
}

$pdo->beginTransaction();

try {
    // جلب عناصر السلة
    $stmt = $pdo->prepare("
        SELECT ci.*, p.price, p.stock, p.name as product_name
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $items = $stmt->fetchAll();

    if (empty($items)) jsonResponse(['error' => 'السلة فارغة'], 400);

    // التحقق من المخزون
    foreach ($items as $item) {
        if ($item['stock'] < $item['quantity']) {
            throw new Exception("المنتج {$item['product_name']} غير متوفر بالكمية المطلوبة");
        }
    }

    $deliveryFee = (float)(getenv('DELIVERY_FEE') ?: 5.00);
    $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
    $total = $subtotal + $deliveryFee;

    // الدفع من المحفظة
    if ($paymentMethod === 'wallet') {
        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
        $stmt->execute([$_SESSION['user_id']]);
        $balance = (float)$stmt->fetchColumn();

        if ($balance < $total) {
            throw new Exception('رصيد المحفظة غير كافٍ');
        }

        // خصم من الرصيد
        $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?")
            ->execute([$total, $_SESSION['user_id']]);

        // تسجيل الحركة
        $pdo->prepare("INSERT INTO wallet_transactions (user_id, amount, type, description) VALUES (?, ?, 'purchase', ?)")
            ->execute([$_SESSION['user_id'], $total, "شراء - طلب"]);

        $paymentStatus = 'paid';
    } else {
        $paymentStatus = $paymentMethod === 'gateway' ? 'pending' : 'pending';
    }

    // إنشاء الطلب
    $orderNumber = generateOrderNumber();
    $stmt = $pdo->prepare("
        INSERT INTO orders (order_number, user_id, subtotal, delivery_fee, total, payment_method, payment_status, address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$orderNumber, $_SESSION['user_id'], $subtotal, $deliveryFee, $total, $paymentMethod, $paymentStatus, $address]);
    $orderId = $pdo->lastInsertId();

    // إضافة عناصر الطلب (تثبيت الأسعار)
    $stmt = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, quantity, price_at_purchase)
        VALUES (?, ?, ?, ?, ?)
    ");
    foreach ($items as $item) {
        $stmt->execute([$orderId, $item['product_id'], $item['product_name'], $item['quantity'], $item['price']]);
    }

    // تحديث المخزون وعداد المبيعات
    $pdo->prepare("UPDATE products SET stock = stock - ?, sales_count = sales_count + ? WHERE id = ?");
    foreach ($items as $item) {
        $pdo->prepare("UPDATE products SET stock = stock - ?, sales_count = sales_count + ? WHERE id = ?")
            ->execute([$item['quantity'], $item['quantity'], $item['product_id']]);
    }

    // تفريغ السلة
    $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$_SESSION['user_id']]);

    $pdo->commit();
    jsonResponse(['success' => true, 'order_number' => $orderNumber, 'order_id' => $orderId]);

} catch (Exception $e) {
    $pdo->rollBack();
    jsonResponse(['error' => $e->getMessage()], 400);
}
```

---

## 6. المرحلة 3 — المحفظة والطلبات

### 6.1 المحفظة الإلكترونية

#### wallet.php

```php
<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
requireLogin();

// جلب الرصيد
$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$balance = $stmt->fetchColumn();

// جلب آخر الحركات
$stmt = $pdo->prepare("
    SELECT * FROM wallet_transactions
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 20
");
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll();
?>
<!-- HTML صفحة المحفظة -->
```

#### api/wallet/charge.php — شحن المحفظة

```php
<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$data = json_decode(file_get_contents('php://input'), true);
$amount = (float)($data['amount'] ?? 0);

if ($amount <= 0) jsonResponse(['error' => 'المبلغ غير صالح'], 400);

// هنا يتم الربط مع بوابة الدفع الخارجية
// بعد تأكيد البوابة:

$pdo->beginTransaction();
$pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")
    ->execute([$amount, $_SESSION['user_id']]);
$pdo->prepare("INSERT INTO wallet_transactions (user_id, amount, type, description) VALUES (?, ?, 'charge', ?)")
    ->execute([$_SESSION['user_id'], $amount, "شحن المحفظة عبر بوابة الدفع"]);
$pdo->commit();

jsonResponse(['success' => true, 'new_balance' => ...]);
```

### 6.2 إدارة الطلبات

#### api/orders/track.php

```php
<?php
require_once __DIR__ . '/../../includes/db.php';

$orderNumber = $_GET['order_number'] ?? '';

$stmt = $pdo->prepare("
    SELECT o.*, oi.product_name, oi.quantity, oi.price_at_purchase
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE o.order_number = ?
");
$stmt->execute([$orderNumber]);
$rows = $stmt->fetchAll();

if (empty($rows)) jsonResponse(['error' => 'الطلب غير موجود'], 404);

$order = [
    'order_number' => $rows[0]['order_number'],
    'status' => $rows[0]['status'],
    'total' => $rows[0]['total'],
    'payment_method' => $rows[0]['payment_method'],
    'created_at' => $rows[0]['created_at'],
    'items' => array_map(fn($r) => [
        'name' => $r['product_name'],
        'quantity' => $r['quantity'],
        'price' => $r['price_at_purchase']
    ], $rows)
];

jsonResponse($order);
```

---

## 7. المرحلة 4 — البوت الذكي

### 7.1 واجهة البوت (CSS + JS)

#### includes/bot-widget.php

```php
<?php
// يُدرج في footer.php
startSession();
$sessionId = session_id();
?>
<div id="bot-widget" class="bot-widget">
    <button id="bot-toggle" class="bot-toggle" onclick="toggleBot()">
        💬 <span>المساعد</span>
    </button>
    <div id="bot-panel" class="bot-panel hidden">
        <div class="bot-header">
            <span>🤖 المساعد الذكي</span>
            <button onclick="toggleBot()">✕</button>
        </div>
        <div id="bot-messages" class="bot-messages"></div>
        <div class="bot-input">
            <input type="text" id="bot-input" placeholder="اكتب رسالتك..."
                   onkeypress="if(event.key==='Enter')sendMessage()">
            <button onclick="sendMessage()">إرسال</button>
        </div>
    </div>
</div>
```

#### assets/js/bot.js

```javascript
let botOpen = false;

function toggleBot() {
    botOpen = !botOpen;
    document.getElementById('bot-panel').classList.toggle('hidden');
}

async function sendMessage() {
    const input = document.getElementById('bot-input');
    const msg = input.value.trim();
    if (!msg) return;

    addMessage(msg, 'user');
    input.value = '';

    const res = await fetch('/api/bot/chat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: msg })
    });
    const data = await res.json();
    addMessage(data.response, 'bot');
}

function addMessage(text, sender) {
    const div = document.createElement('div');
    div.className = `bot-msg ${sender}`;
    div.textContent = text;
    document.getElementById('bot-messages').appendChild(div);
    div.scrollIntoView();
}
```

### 7.2 منطق البوت (PHP)

#### api/bot/chat.php

```php
<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
startSession();

$data = json_decode(file_get_contents('php://input'), true);
$message = trim($data['message'] ?? '');
$userId = isLoggedIn() ? $_SESSION['user_id'] : null;
$sessionId = session_id();

if (empty($message)) jsonResponse(['error' => 'رسالة فارغة'], 400);

$response = '';
$source = 'default';

// 1. هل الرسالة تحتوي رقم طلب؟
if (preg_match('/ORD-\d{8}-\d{3}/', $message, $matches)) {
    $stmt = $pdo->prepare("
        SELECT status, total, created_at FROM orders WHERE order_number = ?
    ");
    $stmt->execute([$matches[0]]);
    $order = $stmt->fetch();
    if ($order) {
        $statusMap = [
            'reviewing' => 'قيد المراجعة',
            'confirmed' => 'تم التأكيد',
            'shipping' => 'قيد الشحن',
            'delivered' => 'تم التوصيل ✅'
        ];
        $response = "طلبك {$matches[0]} حالته: {$statusMap[$order['status']]} | الإجمالي: {$order['total']} | تاريخ: {$order['created_at']}";
        $source = 'order_status';
    }
}

// 2. بحث في قاعدة المعرفة بالكلمات المفتاحية
if (empty($response)) {
    $words = explode(' ', $message);
    $words = array_filter($words, fn($w) => mb_strlen($w) > 2);

    if (!empty($words)) {
        $conditions = [];
        $params = [];
        foreach ($words as $word) {
            $conditions[] = "keywords LIKE ?";
            $params[] = "%$word%";
        }
        $where = implode(' OR ', $conditions);

        $stmt = $pdo->prepare("
            SELECT answer, MATCH(keywords) AGAINST(? IN BOOLEAN MODE) as relevance
            FROM bot_knowledge
            WHERE ($where) AND is_active = 1
            ORDER BY relevance DESC
            LIMIT 1
        ");
        // Simplified: just use LIKE matching
        $stmt = $pdo->prepare("
            SELECT answer FROM bot_knowledge
            WHERE ($where) AND is_active = 1
            LIMIT 1
        ");
        $stmt->execute($params);
        $kb = $stmt->fetch();

        if ($kb) {
            $response = $kb['answer'];
            $source = 'knowledge_base';
        }
    }
}

// 3. رسالة افتراضية
if (empty($response)) {
    $response = "عذراً، لم أتمكن من فهم سؤالك. يمكنك:\n• كتابة رقم الطلب (مثل ORD-20260413-001) لمعرفة حالته\n• سؤالي عن منتج معين\n• التواصل مع خدمة العملاء";
}

// حفظ المحادثة
$pdo->prepare("INSERT INTO chat_logs (user_id, session_id, message, response, source) VALUES (?, ?, ?, ?, ?)")
    ->execute([$userId, $sessionId, $message, $response, $source]);

jsonResponse(['response' => $response]);
```

---

## 8. المرحلة 5 — لوحة تحكم المدير

### 8.1 الحماية

كل صفحة في `/admin/` تبدأ بـ:

```php
<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();
```

### 8.2 لوحة المعلومات — admin/index.php

```php
<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// إحصائيات سريعة
$stats = [
    'total_sales' => $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'reviewing'")->fetchColumn(),
    'total_orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'pending_orders' => $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'reviewing'")->fetchColumn(),
    'total_users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn(),
    'total_products' => $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn(),
];

// أكثر المنتجات مبيعاً
$topProducts = $pdo->query("
    SELECT p.name, p.sales_count, p.price
    FROM products p
    WHERE p.status = 'active'
    ORDER BY p.sales_count DESC
    LIMIT 10
")->fetchAll();

// آخر الطلبات
$recentOrders = $pdo->query("
    SELECT o.*, u.name as customer_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
    LIMIT 10
")->fetchAll();
?>
<!-- HTML لوحة المعلومات -->
```

### 8.3 إدارة المنتجات — admin/products.php

```php
<?php
// CRUD كامل: قائمة + إضافة + تعديل + حذف
// مع دعم رفع صور متعددة
// وتحديد الأقسام والشركات والهااشتاغات
```

### 8.4 التقارير — admin/reports.php

```php
<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$period = $_GET['period'] ?? 'month';

// مبيعات حسب الفترة
$sales = $pdo->prepare("
    SELECT DATE(created_at) as date, SUM(total) as total, COUNT(*) as orders
    FROM orders
    WHERE created_at >= ?
    GROUP BY DATE(created_at)
    ORDER BY date
");
// ... حسب الفترة المختارة

// الأكثر مبيعاً
$topSelling = $pdo->query("
    SELECT p.name, SUM(oi.quantity) as total_qty, SUM(oi.quantity * oi.price_at_purchase) as revenue
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY oi.product_id
    ORDER BY total_qty DESC
    LIMIT 20
")->fetchAll();
?>
<!-- HTML التقارير مع رسوم بيانية بسيطة (Chart.js) -->
```

---

## 9. المرحلة 6 — النشر والأمان

### 9.1 خيارات الاستضافة

| الخيار | المميزات | التكلفة |
|--------|---------|---------|
| استضافة مشتركة (cPanel) | MySQL + PHP جاهز، سهل | $3-10/شهر |
| VPS (Contabo/Hetzner) | تحكم كامل، أداء أفضل | $5-15/شهر |
| GitHub Pages + API خارجي | Frontend مجاني + API مدفوع | مجاني + API |
| InfinityFree | استضافة PHP+MySQL مجانية | مجاني |

### 9.2 خطوات النشر

```bash
# 1. رفع الكود على GitHub
git add .
git commit -m "Initial release - electro store"
git push origin main

# 2. على السيرفر/الاستضافة
git clone https://github.com/USERNAME/electro-store.git
cd electro-store

# 3. إعداد البيئة
cp .env.example .env
nano .env  # أدخل بيانات قاعدة البيانات

# 4. إنشاء قاعدة البيانات
mysql -u root -p < sql/schema.sql
mysql -u root -p electro_store < sql/seed.sql

# 5. صلاحيات المجلدات
chmod 755 assets/images/products/
chmod 755 assets/images/categories/
chmod 755 assets/images/manufacturers/

# 6. تأمين الملفات الحساسة
# في .htaccess:
```

#### .htaccess — حماية الملفات

```apache
# منع الوصول للملفات الحساسة
<FilesMatch "\.(env|sql|md|git)">
    Order allow,deny
    Deny from all
</FilesMatch>

# منع تصفح المجلدات
Options -Indexes

#_force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# توجيه لـ index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

### 9.3 الأمان

```php
// config/constants.php
define('SESSION_LIFETIME', 3600);       // ساعة واحدة
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_MINUTES', 15);

// إضافة في includes/functions.php
function rateLimitLogin($email, $pdo) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM login_attempts
        WHERE email = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)
    ");
    $stmt->execute([$email, LOGIN_LOCKOUT_MINUTES]);
    return $stmt->fetchColumn() >= MAX_LOGIN_ATTEMPTS;
}

// CSRF Protection
function generateCSRF() {
    startSession();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

function verifyCSRF($token) {
    startSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// XSS Prevention
function sanitize($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}
```

---

## 10. الجدول الزمني

| الأسبوع | المرحلة | المهام | المخرجات |
|---------|---------|--------|---------|
| 1 | 0 + 1 | بنية تحتية + مصادقة + عرض منتجات | المستخدم يقدر يسجل ويتصفح |
| 2 | 2 | سلة + عملية شراء كاملة | سلة شغالة + طلبات |
| 3 | 3 | محفظة + إدارة طلبات | دفع + تتبع |
| 4 | 4 + 5 | بوت ذكي + لوحة تحكم | نظام كامل |
| 5 | 6 | نشر + أمان + اختبارات | موقع مباشر |

**الإجمالي: ~5 أسابيع**

---

## 📋 Checklist — قبل التسليم

### الواجهة (Frontend)
- [ ] تصميم متجاوب (Mobile + Desktop)
- [ ] RTL كامل
- [ ] AJAX بدون إعادة تحميل
- [ ] رسائل خطأ واضحة
- [ ] loading states
- [ ] البوت العائم شغال

### الخلفية (Backend)
- [ ] CRUD كل الجداول
- [ ] Prepared Statements في كل استعلام
- [ ] bcrypt لتشفير كلمات المرور
- [ ] حماية CSRF
- [ ] Rate Limiting
- [ ] تثبيت الأسعار لحظة الشراء

### الأمان
- [ ] HTTPS
- [ ] .htaccess يمنع الوصول للملفات الحساسة
- [ ] SESSION hijacking protection
- [ ] XSS prevention (sanitize كل output)
- [ ] SQL injection prevention (PDO + prepared)

### الاختبار
- [ ] تسجيل + دخول + خروج
- [ ] تصفح + بحث + فلترة
- [ ] سلة كاملة (إضافة + تعديل + حذف)
- [ ] شراء بثلاث طرق دفع
- [ ] محفظة (شحن + دفع + سجل)
- [ ] بوت (أسئلة + حالة طلب)
- [ ] لوحة تحكم (كل الأقسام)

---

_الظل 🌑 — خطة تنفيذ كاملة_
