<?php
// ===================================
// معالجة إرسال الطلب - submit_order.php
// ===================================

// تضمين ملف الاتصال بقاعدة البيانات
include 'config.php';

// التحقق من أن الطلب تم إرساله بطريقة POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // استلام البيانات من النموذج
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $order_data = $_POST['order_data'];
    
    // تحويل بيانات الطلب من JSON إلى مصفوفة
    $cart = json_decode($order_data, true);
    
    // التحقق من وجود منتجات في السلة
    if (empty($cart)) {
        // إذا كانت السلة فارغة
        echo "<script>alert('السلة فارغة!'); window.location.href='cart.php';</script>";
        exit();
    }
    
    // إدراج الطلب في جدول orders
    $sql_order = "INSERT INTO orders (customer_name, customer_phone, notes, status) 
                  VALUES ('$customer_name', '$customer_phone', '$notes', 'جديد')";
    
    // تنفيذ الاستعلام
    if (mysqli_query($conn, $sql_order)) {
        
        // الحصول على رقم الطلب الذي تم إدراجه
        $order_id = mysqli_insert_id($conn);
        
        // إدراج تفاصيل الطلب في جدول order_items
        foreach ($cart as $item) {
            $product_id = mysqli_real_escape_string($conn, $item['id']);
            $product_name = mysqli_real_escape_string($conn, $item['name']);
            $product_price = mysqli_real_escape_string($conn, $item['price']);
            $quantity = mysqli_real_escape_string($conn, $item['quantity']);
            
            // استعلام إدراج عنصر الطلب
            $sql_item = "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity) 
                        VALUES ('$order_id', '$product_id', '$product_name', '$product_price', '$quantity')";
            
            // تنفيذ الاستعلام
            mysqli_query($conn, $sql_item);
        }
        
        // رسالة نجاح
        $success = true;
        $order_number = $order_id;
        
    } else {
        // في حالة وجود خطأ
        echo "<script>alert('حدث خطأ أثناء إرسال الطلب. الرجاء المحاولة مرة أخرى.'); window.location.href='cart.php';</script>";
        exit();
    }
    
} else {
    // إذا تم الوصول للصفحة بطريقة خاطئة
    header('Location: cart.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- عنوان الصفحة -->
    <title>تأكيد الطلب - لقمة جدتي</title>
    
    <!-- ربط ملف التنسيق CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- خط عربي جميل من Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* تنسيقات خاصة بصفحة التأكيد */
        .success-container {
            max-width: 600px;
            margin: 80px auto;
            text-align: center;
            background-color: #fff;
            padding: 50px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .success-icon {
            font-size: 5rem;
            color: #4caf50;
            margin-bottom: 20px;
        }
        
        .success-container h2 {
            color: #6d4c41;
            margin-bottom: 15px;
            font-size: 2rem;
        }
        
        .order-number {
            background-color: #fff3e0;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-right: 4px solid #ff8a65;
        }
        
        .order-number strong {
            font-size: 1.5rem;
            color: #ff8a65;
        }
        
        .success-message {
            color: #5d4037;
            line-height: 1.8;
            margin-bottom: 30px;
        }
    </style>
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
                </ul>
            </nav>
        </div>
    </header>

    <!-- ===================================
         محتوى الصفحة
         =================================== -->
    <div class="container">
        <div class="success-container">
            <!-- أيقونة النجاح -->
            <div class="success-icon">✓</div>
            
            <!-- عنوان النجاح -->
            <h2>تم إرسال طلبك بنجاح!</h2>
            
            <!-- رقم الطلب -->
            <div class="order-number">
                <p>رقم طلبك:</p>
                <strong>#<?php echo $order_number; ?></strong>
            </div>
            
            <!-- رسالة التأكيد -->
            <div class="success-message">
                <p>شكراً لك <strong><?php echo htmlspecialchars($customer_name); ?></strong></p>
                <p>تم استلام طلبك بنجاح وسنتواصل معك قريباً على رقم:</p>
                <p><strong><?php echo htmlspecialchars($customer_phone); ?></strong></p>
                <p>لتأكيد الطلب وترتيب موعد التسليم.</p>
            </div>
            
            <!-- أزرار الإجراءات -->
            <a href="products.php" class="btn btn-primary">تصفح المزيد من المنتجات</a>
            <a href="index.php" class="btn btn-secondary" style="margin-right: 10px;">العودة للرئيسية</a>
        </div>
    </div>

    <!-- ===================================
         تذييل الموقع (Footer)
         =================================== -->
    <footer class="footer" style="margin-top: 80px;">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2024 لقمة جدتي - جميع الحقوق محفوظة</p>
            </div>
        </div>
    </footer>

    <!-- تفريغ السلة بعد نجاح الطلب -->
    <script>
        // تفريغ السلة من localStorage
        localStorage.removeItem('cart');
    </script>

</body>
</html>
<?php
// إغلاق الاتصال بقاعدة البيانات
mysqli_close($conn);
?>
