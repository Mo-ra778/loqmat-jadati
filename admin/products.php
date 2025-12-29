<?php
// ===================================
// إدارة المنتجات - لوحة التحكم
// admin/products.php
// ===================================

// تضمين ملف التحقق من تسجيل الدخول
include 'check_login.php';

// تضمين ملف الاتصال بقاعدة البيانات
include '../config.php';

// متغيرات للرسائل
$success_message = '';
$error_message = '';

// معالجة حذف منتج
if (isset($_GET['delete'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // حذف المنتج من قاعدة البيانات
    $delete_query = "DELETE FROM products WHERE id = '$product_id'";
    
    if (mysqli_query($conn, $delete_query)) {
        $success_message = 'تم حذف المنتج بنجاح';
    } else {
        $error_message = 'حدث خطأ أثناء حذف المنتج';
    }
}

// معالجة إضافة/تعديل منتج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // استلام البيانات من النموذج
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    
    // معالجة الصورة
    $image_name = '';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // الحصول على معلومات الصورة
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_path = '../uploads/' . $image_name;
        
        // نقل الصورة إلى مجلد uploads
        move_uploaded_file($image_tmp, $image_path);
    }
    
    // التحقق من وضع التعديل أو الإضافة
    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        // وضع التعديل
        $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
        
        // بناء استعلام التحديث
        if ($image_name) {
            // إذا تم رفع صورة جديدة
            $update_query = "UPDATE products SET 
                            name = '$name',
                            description = '$description',
                            price = '$price',
                            category = '$category',
                            image = '$image_name',
                            is_available = '$is_available'
                            WHERE id = '$product_id'";
        } else {
            // إذا لم يتم رفع صورة جديدة
            $update_query = "UPDATE products SET 
                            name = '$name',
                            description = '$description',
                            price = '$price',
                            category = '$category',
                            is_available = '$is_available'
                            WHERE id = '$product_id'";
        }
        
        if (mysqli_query($conn, $update_query)) {
            $success_message = 'تم تحديث المنتج بنجاح';
        } else {
            $error_message = 'حدث خطأ أثناء تحديث المنتج';
        }
        
    } else {
        // وضع الإضافة
        
        if (!$image_name) {
            $image_name = 'default.jpg'; // صورة افتراضية
        }
        
        $insert_query = "INSERT INTO products (name, description, price, category, image, is_available) 
                        VALUES ('$name', '$description', '$price', '$category', '$image_name', '$is_available')";
        
        if (mysqli_query($conn, $insert_query)) {
            $success_message = 'تم إضافة المنتج بنجاح';
        } else {
            $error_message = 'حدث خطأ أثناء إضافة المنتج';
        }
    }
}

// جلب بيانات منتج للتعديل
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_query = "SELECT * FROM products WHERE id = '$edit_id'";
    $edit_result = mysqli_query($conn, $edit_query);
    $edit_product = mysqli_fetch_assoc($edit_result);
}

// جلب جميع المنتجات
$products_query = "SELECT * FROM products ORDER BY created_at DESC";
$products_result = mysqli_query($conn, $products_query);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- عنوان الصفحة -->
    <title>إدارة المنتجات - لوحة التحكم</title>
    
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
            <a href="products.php" class="nav-item active">
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
            <h1>إدارة المنتجات</h1>
            <p>إضافة وتعديل وحذف المنتجات</p>
        </header>

        <!-- عرض الرسائل -->
        <?php if ($success_message): ?>
            <div class="alert alert-success">✓ <?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-error">⚠️ <?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- نموذج إضافة/تعديل منتج -->
        <div class="content-section">
            <div class="section-header">
                <h2><?php echo $edit_product ? 'تعديل منتج' : 'إضافة منتج جديد'; ?></h2>
            </div>

            <form method="POST" enctype="multipart/form-data">
                
                <!-- حقل مخفي لمعرف المنتج في حالة التعديل -->
                <?php if ($edit_product): ?>
                    <input type="hidden" name="product_id" value="<?php echo $edit_product['id']; ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <!-- اسم المنتج -->
                    <div class="form-group">
                        <label for="name">اسم المنتج *</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo $edit_product ? $edit_product['name'] : ''; ?>" 
                               required>
                    </div>

                    <!-- السعر -->
                    <div class="form-group">
                        <label for="price">السعر (ريال) *</label>
                        <input type="number" step="0.01" id="price" name="price" 
                               value="<?php echo $edit_product ? $edit_product['price'] : ''; ?>" 
                               required>
                    </div>
                </div>

                <div class="form-row">
                    <!-- التصنيف -->
                    <div class="form-group">
                        <label for="category">التصنيف *</label>
                        <input type="text" id="category" name="category" 
                               value="<?php echo $edit_product ? $edit_product['category'] : ''; ?>" 
                               placeholder="مثال: مخبوزات تراثية" 
                               required>
                    </div>

                    <!-- الصورة -->
                    <div class="form-group">
                        <label for="image">صورة المنتج <?php echo $edit_product ? '' : '*'; ?></label>
                        <input type="file" id="image" name="image" accept="image/*"
                               <?php echo $edit_product ? '' : 'required'; ?>>
                        <?php if ($edit_product && $edit_product['image']): ?>
                            <small style="color: #999;">الصورة الحالية: <?php echo $edit_product['image']; ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- الوصف -->
                <div class="form-group">
                    <label for="description">الوصف *</label>
                    <textarea id="description" name="description" rows="4" required><?php echo $edit_product ? $edit_product['description'] : ''; ?></textarea>
                </div>

                <!-- متوفر -->
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_available" value="1" 
                               <?php echo (!$edit_product || $edit_product['is_available']) ? 'checked' : ''; ?>>
                        المنتج متوفر
                    </label>
                </div>

                <!-- أزرار الإجراءات -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $edit_product ? 'تحديث المنتج' : 'إضافة المنتج'; ?>
                    </button>
                    
                    <?php if ($edit_product): ?>
                        <a href="products.php" class="btn btn-secondary">إلغاء التعديل</a>
                    <?php endif; ?>
                </div>

            </form>
        </div>

        <!-- جدول المنتجات -->
        <div class="content-section">
            <div class="section-header">
                <h2>جميع المنتجات</h2>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>اسم المنتج</th>
                            <th>التصنيف</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // التحقق من وجود منتجات
                        if (mysqli_num_rows($products_result) > 0) {
                            // عرض كل منتج
                            while ($product = mysqli_fetch_assoc($products_result)) {
                                ?>
                                <tr>
                                    <td>
                                        <img src="../uploads/<?php echo $product['image']; ?>" 
                                             alt="<?php echo $product['name']; ?>"
                                             class="product-img">
                                    </td>
                                    <td><?php echo $product['name']; ?></td>
                                    <td><?php echo $product['category']; ?></td>
                                    <td><?php echo number_format($product['price'], 2); ?> ريال</td>
                                    <td>
                                        <span class="status-badge <?php echo $product['is_available'] ? 'status-delivered' : 'status-processing'; ?>">
                                            <?php echo $product['is_available'] ? 'متوفر' : 'غير متوفر'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="products.php?edit=<?php echo $product['id']; ?>" 
                                           class="btn-action btn-edit">تعديل</a>
                                        <a href="products.php?delete=<?php echo $product['id']; ?>" 
                                           class="btn-action btn-delete"
                                           onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">حذف</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #999;">لا توجد منتجات بعد</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>
</html>
<?php
// إغلاق الاتصال بقاعدة البيانات
mysqli_close($conn);
?>
