<?php
// ===================================
// لوحة التحكم الرئيسية
// admin/dashboard.php
// ===================================

// تضمين ملف التحقق من تسجيل الدخول
include 'check_login.php';

// تضمين ملف الاتصال بقاعدة البيانات
include '../config.php';

// جلب عدد المنتجات
$products_count_query = "SELECT COUNT(*) as total FROM products";
$products_count_result = mysqli_query($conn, $products_count_query);
$products_count = mysqli_fetch_assoc($products_count_result)['total'];

// جلب عدد الطلبات الجديدة
$new_orders_query = "SELECT COUNT(*) as total FROM orders WHERE status = 'جديد'";
$new_orders_result = mysqli_query($conn, $new_orders_query);
$new_orders_count = mysqli_fetch_assoc($new_orders_result)['total'];

// جلب عدد الطلبات الكلي
$total_orders_query = "SELECT COUNT(*) as total FROM orders";
$total_orders_result = mysqli_query($conn, $total_orders_query);
$total_orders_count = mysqli_fetch_assoc($total_orders_result)['total'];

// جلب آخر 5 طلبات
$recent_orders_query = "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5";
$recent_orders_result = mysqli_query($conn, $recent_orders_query);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- عنوان الصفحة -->
    <title>لوحة التحكم - لقمة جدتي</title>
    
    <!-- ربط ملف التنسيق CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="admin-style.css">
    
    <!-- خط عربي جميل من Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">

    <!-- القائمة الجانبية -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2>لقمة جدتي</h2>
            <p>لوحة التحكم</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item active">
                <span class="nav-icon">📊</span>
                <span>الرئيسية</span>
            </a>
            <a href="products.php" class="nav-item">
                <span class="nav-icon">🍽️</span>
                <span>المنتجات</span>
            </a>
            <a href="orders.php" class="nav-item">
                <span class="nav-icon">📦</span>
                <span>الطلبات</span>
            </a>
            <a href="logout.php" class="nav-item logout">
                <span class="nav-icon">🚪</span>
                <span>تسجيل الخروج</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <p>مرحباً، <strong><?php echo $_SESSION['admin_username']; ?></strong></p>
        </div>
    </aside>

    <!-- المحتوى الرئيسي -->
    <main class="admin-content">
        
        <!-- رأس الصفحة -->
        <header class="content-header">
            <h1>لوحة التحكم الرئيسية</h1>
            <p>مرحباً بك في نظام إدارة موقع لقمة جدتي</p>
        </header>

        <!-- بطاقات الإحصائيات -->
        <div class="stats-grid">
            
            <!-- بطاقة المنتجات -->
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #ff8a65;">🍽️</div>
                <div class="stat-details">
                    <h3>المنتجات</h3>
                    <p class="stat-number"><?php echo $products_count; ?></p>
                    <a href="products.php" class="stat-link">إدارة المنتجات ←</a>
                </div>
            </div>

            <!-- بطاقة الطلبات الجديدة -->
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #4caf50;">📦</div>
                <div class="stat-details">
                    <h3>طلبات جديدة</h3>
                    <p class="stat-number"><?php echo $new_orders_count; ?></p>
                    <a href="orders.php?status=جديد" class="stat-link">عرض الطلبات ←</a>
                </div>
            </div>

            <!-- بطاقة إجمالي الطلبات -->
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #2196f3;">📋</div>
                <div class="stat-details">
                    <h3>إجمالي الطلبات</h3>
                    <p class="stat-number"><?php echo $total_orders_count; ?></p>
                    <a href="orders.php" class="stat-link">جميع الطلبات ←</a>
                </div>
            </div>

        </div>

        <!-- قسم آخر الطلبات -->
        <div class="content-section">
            <div class="section-header">
                <h2>آخر الطلبات</h2>
                <a href="orders.php" class="btn btn-secondary">عرض الكل</a>
            </div>

            <!-- جدول الطلبات -->
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>اسم العميل</th>
                            <th>رقم الهاتف</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // التحقق من وجود طلبات
                        if (mysqli_num_rows($recent_orders_result) > 0) {
                            // عرض كل طلب
                            while ($order = mysqli_fetch_assoc($recent_orders_result)) {
                                // تحديد لون الحالة
                                $status_class = '';
                                if ($order['status'] == 'جديد') $status_class = 'status-new';
                                elseif ($order['status'] == 'تم التواصل') $status_class = 'status-contacted';
                                elseif ($order['status'] == 'قيد التنفيذ') $status_class = 'status-processing';
                                elseif ($order['status'] == 'تم التسليم') $status_class = 'status-delivered';
                                
                                ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo $order['customer_name']; ?></td>
                                    <td><?php echo $order['customer_phone']; ?></td>
                                    <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $order['status']; ?></span></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <a href="orders.php?view=<?php echo $order['id']; ?>" class="btn-action btn-view">عرض</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #999;">لا توجد طلبات بعد</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- روابط سريعة -->
        <div class="quick-actions-section">
            <h2>إجراءات سريعة</h2>
            <div class="quick-actions-grid">
                <a href="products.php?action=add" class="quick-action-card">
                    <span class="qa-icon">➕</span>
                    <span>إضافة منتج جديد</span>
                </a>
                <a href="orders.php?status=جديد" class="quick-action-card">
                    <span class="qa-icon">📦</span>
                    <span>الطلبات الجديدة</span>
                </a>
                <a href="../index.php" target="_blank" class="quick-action-card">
                    <span class="qa-icon">🌐</span>
                    <span>زيارة الموقع</span>
                </a>
            </div>
        </div>

    </main>

</body>
</html>
<?php
// إغلاق الاتصال بقاعدة البيانات
mysqli_close($conn);
?>
