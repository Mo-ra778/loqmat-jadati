<?php
// ===================================
// إدارة الطلبات - لوحة التحكم
// admin/orders.php
// ===================================

// تضمين ملف التحقق من تسجيل الدخول
include 'check_login.php';

// تضمين ملف الاتصال بقاعدة البيانات
include '../config.php';

// متغيرات للرسائل
$success_message = '';

// معالجة تحديث حالة الطلب
if (isset($_POST['update_status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_query = "UPDATE orders SET status = '$new_status' WHERE id = '$order_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $success_message = 'تم تحديث حالة الطلب بنجاح';
    }
}

// التصفية حسب الحالة
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// بناء الاستعلام
if ($status_filter) {
    $orders_query = "SELECT * FROM orders WHERE status = '$status_filter' ORDER BY created_at DESC";
} else {
    $orders_query = "SELECT * FROM orders ORDER BY created_at DESC";
}

$orders_result = mysqli_query($conn, $orders_query);

// عرض تفاصيل طلب محدد
$view_order = null;
$order_items = null;
if (isset($_GET['view'])) {
    $view_id = mysqli_real_escape_string($conn, $_GET['view']);
    
    // جلب بيانات الطلب
    $order_query = "SELECT * FROM orders WHERE id = '$view_id'";
    $order_result = mysqli_query($conn, $order_query);
    $view_order = mysqli_fetch_assoc($order_result);
    
    // جلب عناصر الطلب
    if ($view_order) {
        $items_query = "SELECT * FROM order_items WHERE order_id = '$view_id'";
        $order_items = mysqli_query($conn, $items_query);
    }
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- عنوان الصفحة -->
    <title>إدارة الطلبات - لوحة التحكم</title>
    
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
            <a href="dashboard.php" class="nav-item">
                <span class="nav-icon">📊</span>
                <span>الرئيسية</span>
            </a>
            <a href="products.php" class="nav-item">
                <span class="nav-icon">🍽️</span>
                <span>المنتجات</span>
            </a>
            <a href="orders.php" class="nav-item active">
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
            <h1>إدارة الطلبات</h1>
            <p>عرض ومتابعة جميع الطلبات</p>
        </header>

        <!-- عرض الرسائل -->
        <?php if ($success_message): ?>
            <div class="alert alert-success">✓ <?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if ($view_order): ?>
            <!-- عرض تفاصيل الطلب -->
            <div class="content-section">
                <div class="section-header">
                    <h2>تفاصيل الطلب #<?php echo $view_order['id']; ?></h2>
                    <a href="orders.php" class="btn btn-secondary">← العودة للطلبات</a>
                </div>

                <!-- معلومات العميل -->
                <div style="background-color: #f5f5f5; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h3 style="color: #6d4c41; margin-bottom: 15px;">معلومات العميل</h3>
                    <p><strong>الاسم:</strong> <?php echo $view_order['customer_name']; ?></p>
                    <p><strong>رقم الهاتف:</strong> <?php echo $view_order['customer_phone']; ?></p>
                    <p><strong>تاريخ الطلب:</strong> <?php echo date('Y-m-d H:i', strtotime($view_order['created_at'])); ?></p>
                    <?php if ($view_order['notes']): ?>
                        <p><strong>ملاحظات:</strong> <?php echo nl2br($view_order['notes']); ?></p>
                    <?php endif; ?>
                </div>

                <!-- عناصر الطلب -->
                <h3 style="color: #6d4c41; margin-bottom: 15px;">المنتجات المطلوبة</h3>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>المجموع</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        while ($item = mysqli_fetch_assoc($order_items)) {
                            $item_total = $item['product_price'] * $item['quantity'];
                            $total += $item_total;
                            ?>
                            <tr>
                                <td><?php echo $item['product_name']; ?></td>
                                <td><?php echo number_format($item['product_price'], 2); ?> ريال</td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item_total, 2); ?> ريال</td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr style="background-color: #f5f5f5; font-weight: 700;">
                            <td colspan="3">المجموع الكلي</td>
                            <td><?php echo number_format($total, 2); ?> ريال</td>
                        </tr>
                    </tbody>
                </table>

                <!-- تحديث حالة الطلب -->
                <div style="margin-top: 30px; background-color: #fff3e0; padding: 20px; border-radius: 8px;">
                    <h3 style="color: #6d4c41; margin-bottom: 15px;">تحديث حالة الطلب</h3>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $view_order['id']; ?>">
                        <div style="display: flex; gap: 15px; align-items: flex-end;">
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label for="status">الحالة الحالية</label>
                                <select id="status" name="status" class="form-group">
                                    <option value="جديد" <?php echo $view_order['status'] == 'جديد' ? 'selected' : ''; ?>>جديد</option>
                                    <option value="تم التواصل" <?php echo $view_order['status'] == 'تم التواصل' ? 'selected' : ''; ?>>تم التواصل</option>
                                    <option value="قيد التنفيذ" <?php echo $view_order['status'] == 'قيد التنفيذ' ? 'selected' : ''; ?>>قيد التنفيذ</option>
                                    <option value="تم التسليم" <?php echo $view_order['status'] == 'تم التسليم' ? 'selected' : ''; ?>>تم التسليم</option>
                                </select>
                            </div>
                            <button type="submit" name="update_status" class="btn btn-primary">تحديث الحالة</button>
                        </div>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <!-- جدول الطلبات -->
            <div class="content-section">
                <div class="section-header">
                    <h2>جميع الطلبات</h2>
                    <div style="display: flex; gap: 10px;">
                        <a href="orders.php" class="btn btn-secondary">الكل</a>
                        <a href="orders.php?status=جديد" class="btn <?php echo $status_filter == 'جديد' ? 'btn-primary' : 'btn-secondary'; ?>">جديد</a>
                        <a href="orders.php?status=قيد التنفيذ" class="btn <?php echo $status_filter == 'قيد التنفيذ' ? 'btn-primary' : 'btn-secondary'; ?>">قيد التنفيذ</a>
                        <a href="orders.php?status=تم التسليم" class="btn <?php echo $status_filter == 'تم التسليم' ? 'btn-primary' : 'btn-secondary'; ?>">تم التسليم</a>
                    </div>
                </div>

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
                            if (mysqli_num_rows($orders_result) > 0) {
                                // عرض كل طلب
                                while ($order = mysqli_fetch_assoc($orders_result)) {
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
                                    <td colspan="6" style="text-align: center; color: #999;">لا توجد طلبات</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </main>

</body>
</html>
<?php
// إغلاق الاتصال بقاعدة البيانات
mysqli_close($conn);
?>
