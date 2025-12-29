-- ===================================
-- قاعدة بيانات مشروع "لقمة جدتي"
-- Loqmat Jadati - Traditional Yemeni Food Orders
-- ===================================

-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS loqmat_jadati CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE loqmat_jadati;

-- ===================================
-- جدول 1: المنتجات (products)
-- ===================================
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- جدول 2: الطلبات (orders)
-- ===================================
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    notes TEXT,
    status VARCHAR(30) DEFAULT 'جديد',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- جدول 3: تفاصيل الطلبات (order_items)
-- ===================================
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    product_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- جدول 4: المسؤول (admin)
-- ===================================
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- بيانات تجريبية أولية
-- ===================================

-- إضافة حساب المسؤول الافتراضي
-- اسم المستخدم: admin
-- كلمة المرور: admin123
-- ملاحظة: يجب تغيير كلمة المرور بعد أول تسجيل دخول
INSERT INTO admin (username, password) VALUES 
('admin', MD5('admin123'));

-- إضافة منتجات تجريبية
INSERT INTO products (name, description, price, category, image, is_available) VALUES 
('كعك هند', 'كعك يمني تقليدي يُحضّر بالسمن البلدي، مناسب للضيافة والفطور', 50.00, 'مخبوزات تراثية', 'kaak_hind.jpg', 1),
('كعك دخن', 'كعك مصنوع من دقيق الدخن، من المخبوزات الشعبية المعروفة قديمًا', 45.00, 'مخبوزات تراثية', 'kaak_dokhn.jpg', 1),
('اللُّبا', 'أكلة يمنية تراثية تُحضّر حسب الطلب وبالطريقة التقليدية', 80.00, 'أكلات تراثية منزلية', 'luba.jpg', 1);

-- إضافة طلب تجريبي
INSERT INTO orders (customer_name, customer_phone, notes, status) VALUES 
('أحمد محمد', '773123456', 'طلب لمناسبة عائلية', 'جديد');

-- إضافة تفاصيل الطلب التجريبي
INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity) VALUES 
(1, 1, 'كعك هند', 50.00, 3),
(1, 3, 'اللُّبا', 80.00, 1);

-- ===================================
-- انتهى ملف قاعدة البيانات
-- ===================================
