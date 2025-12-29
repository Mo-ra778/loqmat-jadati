<?php
// ===================================
// صفحة السلة - لقمة جدتي
// cart.php
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
    <title>السلة - لقمة جدتي</title>
    
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
                    <li><a href="contact.php">تواصل معنا</a></li>
                    <li><a href="cart.php" class="cart-link active">السلة 🛒 <span id="cart-count" class="cart-count">0</span></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- ===================================
         عنوان الصفحة
         =================================== -->
    <section class="page-header">
        <div class="container">
            <h2>سلة المشتريات</h2>
            <p>راجع طلبك قبل الإرسال</p>
        </div>
    </section>

    <!-- ===================================
         قسم السلة
         =================================== -->
    <section class="cart-section">
        <div class="container">
            
            <!-- رسالة السلة الفارغة (ستظهر عند عدم وجود منتجات) -->
            <div id="empty-cart" class="empty-cart" style="display: none;">
                <div class="empty-cart-icon">🛒</div>
                <h3>سلتك فارغة!</h3>
                <p>لم تقم بإضافة أي منتجات بعد</p>
                <a href="products.php" class="btn btn-primary">تصفح المنتجات</a>
            </div>

            <!-- محتوى السلة (سيظهر عند وجود منتجات) -->
            <div id="cart-content" style="display: none;">
                
                <!-- جدول المنتجات -->
                <div class="cart-items">
                    <h3>المنتجات المختارة</h3>
                    <div id="cart-items-list"></div>
                </div>

                <!-- ملخص الطلب -->
                <div class="cart-summary">
                    <h3>ملخص الطلب</h3>
                    
                    <div class="summary-row">
                        <span>عدد المنتجات:</span>
                        <span id="total-items">0</span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>المجموع الكلي:</span>
                        <span id="total-price">0.00 ريال</span>
                    </div>

                    <div class="cart-note">
                        <p><strong>ملاحظة:</strong> الأسعار المعروضة نهائية. سيتم التواصل معك لتأكيد الطلب وترتيب التسليم.</p>
                    </div>

                    <!-- أزرار الإجراءات -->
                    <button class="btn btn-primary btn-block" onclick="proceedToCheckout()">
                        متابعة الطلب
                    </button>
                    
                    <button class="btn btn-secondary btn-block" onclick="confirmClearCart()">
                        تفريغ السلة
                    </button>
                </div>

            </div>

            <!-- نموذج إرسال الطلب (مخفي افتراضياً) -->
            <div id="checkout-form" style="display: none;">
                <div class="form-container">
                    <h3>أكمل بياناتك لإرسال الطلب</h3>
                    
                    <form id="order-form" method="POST" action="submit_order.php">
                        
                        <!-- حقل الاسم -->
                        <div class="form-group">
                            <label for="customer_name">الاسم الكامل *</label>
                            <input type="text" id="customer_name" name="customer_name" 
                                   placeholder="أدخل اسمك الكامل" required>
                        </div>

                        <!-- حقل رقم الهاتف -->
                        <div class="form-group">
                            <label for="customer_phone">رقم الهاتف *</label>
                            <input type="tel" id="customer_phone" name="customer_phone" 
                                   placeholder="مثال: 773123456" required>
                        </div>

                        <!-- حقل الملاحظات -->
                        <div class="form-group">
                            <label for="notes">ملاحظات إضافية (اختياري)</label>
                            <textarea id="notes" name="notes" rows="4" 
                                      placeholder="أي ملاحظات خاصة أو طلبات إضافية..."></textarea>
                        </div>

                        <!-- حقل مخفي لبيانات الطلب -->
                        <input type="hidden" id="order_data" name="order_data">

                        <!-- أزرار الإرسال -->
                        <button type="submit" class="btn btn-primary btn-block">
                            إرسال الطلب ✓
                        </button>
                        
                        <button type="button" class="btn btn-secondary btn-block" onclick="backToCart()">
                            العودة للسلة
                        </button>

                    </form>
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

    <!-- ===================================
         ملفات JavaScript
         =================================== -->
    <script src="js/cart.js"></script>
    <script src="js/cart-page.js"></script>

</body>
</html>
<?php
// إغلاق الاتصال بقاعدة البيانات
mysqli_close($conn);
?>
