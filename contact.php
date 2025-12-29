<?php
// ===================================
// صفحة التواصل - لقمة جدتي
// contact.php
// ===================================

// تضمين ملف الاتصال بقاعدة البيانات
include 'config.php';

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- عنوان الصفحة -->
    <title>تواصل معنا - لقمة جدتي</title>
    
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
                    <li><a href="products.php">المنتجات</a></li>
                    <li><a href="about.php">من نحن</a></li>
                    <li><a href="contact.php" class="active">تواصل معنا</a></li>
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
            <h2>تواصل معنا</h2>
            <p>نحن سعداء بالتواصل معك والإجابة على استفساراتك</p>
        </div>
    </section>

    <!-- ===================================
         قسم معلومات التواصل
         =================================== -->
    <section class="contact-section">
        <div class="container">
            
            <!-- شبكة معلومات التواصل -->
            <div class="contact-grid">
                
                <!-- الهاتف -->
                <div class="contact-card">
                    <div class="contact-icon">📱</div>
                    <h3>الهاتف</h3>
                    <p><strong>773123456</strong></p>
                    <p class="contact-note">متاح يومياً من 9 صباحاً - 9 مساءً</p>
                    <a href="tel:773123456" class="btn btn-secondary">اتصل الآن</a>
                </div>

                <!-- واتساب -->
                <div class="contact-card">
                    <div class="contact-icon">💬</div>
                    <h3>واتساب</h3>
                    <p><strong>773123456</strong></p>
                    <p class="contact-note">للطلبات والاستفسارات</p>
                    <a href="https://wa.me/967773123456" target="_blank" class="btn btn-primary">راسلنا على واتساب</a>
                </div>

                <!-- الموقع -->
                <div class="contact-card">
                    <div class="contact-icon">📍</div>
                    <h3>الموقع</h3>
                    <p><strong>صنعاء، اليمن</strong></p>
                    <p class="contact-note">التوصيل حسب المنطقة</p>
                </div>

            </div>

            <!-- معلومات إضافية -->
            <div class="info-boxes">
                
                <div class="info-box">
                    <h3>⏰ ساعات العمل</h3>
                    <p>السبت - الخميس: 9:00 صباحاً - 9:00 مساءً</p>
                    <p>الجمعة: 2:00 مساءً - 9:00 مساءً</p>
                </div>

                <div class="info-box">
                    <h3>📦 نظام الطلبات</h3>
                    <p>نعمل بنظام <strong>الطلب المسبق</strong></p>
                    <p>يُفضّل الطلب قبل 24 ساعة على الأقل</p>
                </div>

                <div class="info-box">
                    <h3>🚗 التوصيل</h3>
                    <p>نوفر خدمة التوصيل داخل صنعاء</p>
                    <p>يمكن الاستلام من المطبخ مباشرة</p>
                </div>

            </div>

            <!-- ملاحظة مهمة -->
            <div class="important-note">
                <h3>📌 ملاحظة مهمة</h3>
                <p>
                    نحن <strong>مطبخ منزلي</strong> نعمل بكميات محدودة لضمان أعلى جودة. 
                    لذا نرجو <strong>الطلب المسبق</strong> لنتمكن من تلبية طلبك في الوقت المناسب.
                </p>
                <p>
                    للطلبات الكبيرة أو المناسبات الخاصة، يُرجى التواصل معنا قبل <strong>48 ساعة</strong> على الأقل.
                </p>
            </div>

            <!-- طرق التواصل السريعة -->
            <div class="quick-actions">
                <h3>تواصل معنا الآن</h3>
                <div class="action-buttons">
                    <a href="tel:773123456" class="action-btn">
                        <span class="action-icon">📞</span>
                        <span>اتصال هاتفي</span>
                    </a>
                    
                    <a href="https://wa.me/967773123456" target="_blank" class="action-btn">
                        <span class="action-icon">💬</span>
                        <span>واتساب</span>
                    </a>
                    
                    <a href="products.php" class="action-btn">
                        <span class="action-icon">🛒</span>
                        <span>تصفح المنتجات</span>
                    </a>
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
