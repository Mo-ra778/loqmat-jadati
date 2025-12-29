<?php
// ===================================
// الصفحة الرئيسية - لقمة جدتي
// index.php
// ===================================

// تضمين ملف الاتصال بقاعدة البيانات
include 'config.php';

// جلب 3 منتجات عشوائية لعرضها في الصفحة الرئيسية
$query = "SELECT * FROM products WHERE is_available = 1 ORDER BY RAND() LIMIT 3";
// تنفيذ الاستعلام
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- عنوان الصفحة -->
    <title>لقمة جدتي - أكلات يمنية تراثية</title>
    
    <!-- ربط ملف التنسيق CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- خط عربي جميل من Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- ===================================
         رأس الموقع (Header)
         =================================== -->
    <header class="header">
        <div class="container">
            <!-- شعار الموقع -->
            <div class="logo">
                <h1>لقمة جدتي</h1>
                <p class="tagline">طعم الأصالة اليمنية</p>
            </div>
            
            <!-- قائمة التنقل -->
            <nav class="nav">
                <ul>
                    <li><a href="index.php" class="active">الرئيسية</a></li>
                    <li><a href="products.php">المنتجات</a></li>
                    <li><a href="about.php">من نحن</a></li>
                    <li><a href="contact.php">تواصل معنا</a></li>
                    <li><a href="cart.php" class="cart-link">السلة 🛒 <span id="cart-count" class="cart-count">0</span></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- ===================================
         القسم الرئيسي (Hero Section)
         =================================== -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>أكلات يمنية تراثية أصيلة</h2>
                <p>نحضّر لكم أشهى الأكلات والمخبوزات اليمنية التقليدية<br>بنفس الطريقة التي كانت تُحضّر في بيوت جداتنا</p>
                <a href="products.php" class="btn btn-primary">تصفح المنتجات</a>
            </div>
        </div>
    </section>

    <!-- ===================================
         قسم التعريف بالمشروع
         =================================== -->
    <section class="about-section">
        <div class="container">
            <div class="section-header">
                <h2>مرحباً بكم في لقمة جدتي</h2>
                <div class="divider"></div>
            </div>
            <p class="about-text">
                نحن مشروع طبخ منزلي يمني شعبي، نقدّم لكم أكلات ومخبوزات تراثية تُحضّر يدويًا بأسلوب تقليدي،
                باستخدام مكونات طبيعية وطرق إعداد أصيلة، مع الحفاظ على الجودة العالية في كل وجبة نقدمها.
            </p>
        </div>
    </section>

    <!-- ===================================
         قسم عرض المنتجات المميزة
         =================================== -->
    <section class="featured-products">
        <div class="container">
            <div class="section-header">
                <h2>منتجاتنا المميزة</h2>
                <div class="divider"></div>
            </div>
            
            <div class="products-grid">
                <?php
                // التحقق من وجود منتجات
                if (mysqli_num_rows($result) > 0) {
                    // عرض كل منتج في حلقة while
                    while ($product = mysqli_fetch_assoc($result)) {
                        ?>
                        <!-- بطاقة المنتج -->
                        <div class="product-card">
                            <!-- صورة المنتج -->
                            <div class="product-image">
                                <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            </div>
                            
                            <!-- معلومات المنتج -->
                            <div class="product-info">
                                <!-- اسم المنتج -->
                                <h3><?php echo $product['name']; ?></h3>
                                
                                <!-- وصف مختصر -->
                                <p class="product-desc">
                                    <?php 
                                    // عرض أول 80 حرف من الوصف فقط
                                    echo mb_substr($product['description'], 0, 80) . '...'; 
                                    ?>
                                </p>
                                
                                <!-- السعر -->
                                <p class="product-price"><?php echo $product['price']; ?> ريال</p>
                                
                                <!-- زر المشاهدة -->
                                <a href="products.php" class="btn btn-secondary">عرض المزيد</a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // رسالة في حالة عدم وجود منتجات
                    echo '<p class="no-products">لا توجد منتجات متوفرة حالياً</p>';
                }
                ?>
            </div>
            
            <!-- زر لرؤية جميع المنتجات -->
            <div class="view-all">
                <a href="products.php" class="btn btn-primary">عرض جميع المنتجات</a>
            </div>
        </div>
    </section>

    <!-- ===================================
         قسم مميزات المشروع
         =================================== -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <!-- ميزة 1 -->
                <div class="feature-box">
                    <div class="feature-icon">🥘</div>
                    <h3>طبخ منزلي تقليدي</h3>
                    <p>جميع الأكلات تُحضّر يدويًا بطرق تقليدية أصيلة</p>
                </div>
                
                <!-- ميزة 2 -->
                <div class="feature-box">
                    <div class="feature-icon">🌾</div>
                    <h3>مكونات طبيعية</h3>
                    <p>نستخدم مكونات طبيعية وسمن بلدي أصلي</p>
                </div>
                
                <!-- ميزة 3 -->
                <div class="feature-box">
                    <div class="feature-icon">⭐</div>
                    <h3>جودة عالية</h3>
                    <p>نحافظ على أعلى معايير الجودة والطعم الأصيل</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         تذييل الموقع (Footer)
         =================================== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- معلومات التواصل -->
                <div class="footer-section">
                    <h3>تواصل معنا</h3>
                    <p>📱 الهاتف: 773123456</p>
                    <p>📍 صنعاء، اليمن</p>
                </div>
                
                <!-- روابط سريعة -->
                <div class="footer-section">
                    <h3>روابط سريعة</h3>
                    <ul>
                        <li><a href="index.php">الرئيسية</a></li>
                        <li><a href="products.php">المنتجات</a></li>
                        <li><a href="about.php">من نحن</a></li>
                        <li><a href="contact.php">تواصل معنا</a></li>
                    </ul>
                </div>
                
                <!-- وصف مختصر -->
                <div class="footer-section">
                    <h3>لقمة جدتي</h3>
                    <p>إحياء المطبخ اليمني التراثي من خلال تقديم أكلات ومخبوزات بلدية أصيلة</p>
                </div>
            </div>
            
            <!-- حقوق النشر -->
            <div class="footer-bottom">
                <p>&copy; 2024 لقمة جدتي - جميع الحقوق محفوظة</p>
            </div>
        </div>
    </footer>

    <!-- ملف JavaScript لتحديث عداد السلة -->
    <script src="js/cart.js"></script>

</body>
</html>
<?php
// إغلاق الاتصال بقاعدة البيانات
mysqli_close($conn);
?>
