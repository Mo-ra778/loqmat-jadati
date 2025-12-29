<?php
// ===================================
// صفحة المنتجات - لقمة جدتي
// products.php
// ===================================

// تضمين ملف الاتصال بقاعدة البيانات
include 'config.php';

// التحقق من وجود تصنيف محدد في الرابط
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'all';

// بناء الاستعلام حسب التصنيف المختار
if ($selected_category == 'all') {
    // إذا كان "الكل"، نجلب جميع المنتجات المتوفرة
    $query = "SELECT * FROM products WHERE is_available = 1 ORDER BY created_at DESC";
} else {
    // إذا كان تصنيف محدد، نجلب المنتجات من هذا التصنيف فقط
    $query = "SELECT * FROM products WHERE is_available = 1 AND category = '" . mysqli_real_escape_string($conn, $selected_category) . "' ORDER BY created_at DESC";
}

// تنفيذ الاستعلام
$result = mysqli_query($conn, $query);

// جلب جميع التصنيفات المتوفرة (لعرضها في القائمة)
$categories_query = "SELECT DISTINCT category FROM products WHERE is_available = 1";
$categories_result = mysqli_query($conn, $categories_query);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- عنوان الصفحة -->
    <title>المنتجات - لقمة جدتي</title>
    
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
                    <li><a href="index.php">الرئيسية</a></li>
                    <li><a href="products.php" class="active">المنتجات</a></li>
                    <li><a href="about.php">من نحن</a></li>
                    <li><a href="contact.php">تواصل معنا</a></li>
                    <li><a href="cart.php" class="cart-link">السلة 🛒 <span id="cart-count" class="cart-count">0</span></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- ===================================
         عنوان الصفحة
         =================================== -->
    <section class="page-header">
        <div class="container">
            <h2>منتجاتنا التراثية</h2>
            <p>تصفح جميع أكلاتنا ومخبوزاتنا اليمنية الأصيلة</p>
        </div>
    </section>

    <!-- ===================================
         قسم المنتجات
         =================================== -->
    <section class="products-section">
        <div class="container">
            
            <!-- قائمة التصنيفات -->
            <div class="categories-filter">
                <h3>التصنيفات:</h3>
                <div class="category-buttons">
                    <!-- زر "الكل" -->
                    <a href="products.php?category=all" class="category-btn <?php echo ($selected_category == 'all') ? 'active' : ''; ?>">
                        الكل
                    </a>
                    
                    <?php
                    // عرض جميع التصنيفات في حلقة
                    while ($cat = mysqli_fetch_assoc($categories_result)) {
                        // التحقق من أن هذا التصنيف هو المختار حالياً
                        $active_class = ($selected_category == $cat['category']) ? 'active' : '';
                        ?>
                        <a href="products.php?category=<?php echo urlencode($cat['category']); ?>" 
                           class="category-btn <?php echo $active_class; ?>">
                            <?php echo $cat['category']; ?>
                        </a>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <!-- شبكة عرض المنتجات -->
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
                                <!-- شارة التصنيف -->
                                <span class="category-badge"><?php echo $product['category']; ?></span>
                            </div>
                            
                            <!-- معلومات المنتج -->
                            <div class="product-info">
                                <!-- اسم المنتج -->
                                <h3><?php echo $product['name']; ?></h3>
                                
                                <!-- الوصف الكامل -->
                                <p class="product-desc"><?php echo $product['description']; ?></p>
                                
                                <!-- السعر -->
                                <p class="product-price"><?php echo number_format($product['price'], 2); ?> ريال</p>
                                
                                <!-- زر الإضافة للسلة -->
                                <button 
                                    class="btn btn-primary add-to-cart" 
                                    data-id="<?php echo $product['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                    data-price="<?php echo $product['price']; ?>"
                                    data-image="<?php echo $product['image']; ?>">
                                    إضافة للسلة 🛒
                                </button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // رسالة في حالة عدم وجود منتجات
                    echo '<p class="no-products">لا توجد منتجات في هذا التصنيف حالياً</p>';
                }
                ?>
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

    <!-- ===================================
         ملف JavaScript لإدارة السلة
         =================================== -->
    <script src="js/cart.js"></script>

</body>
</html>
<?php
// إغلاق الاتصال بقاعدة البيانات
mysqli_close($conn);
?>
