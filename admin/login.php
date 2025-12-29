<?php
// ===================================
// صفحة تسجيل الدخول - لوحة التحكم
// admin/login.php
// ===================================

// بدء الجلسة (Session) لتتبع حالة تسجيل الدخول
session_start();

// تضمين ملف الاتصال بقاعدة البيانات
include '../config.php';

// متغير لتخزين رسالة الخطأ
$error = '';

// التحقق من أن المسؤول مسجل دخول بالفعل
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // إذا كان مسجل دخول، يتم توجيهه للوحة التحكم
    header('Location: dashboard.php');
    exit();
}

// التحقق من إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // استلام البيانات من النموذج
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // تشفير كلمة المرور بنفس الطريقة المستخدمة في قاعدة البيانات (MD5)
    $hashed_password = md5($password);
    
    // الاستعلام للتحقق من بيانات المسؤول
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$hashed_password'";
    
    // تنفيذ الاستعلام
    $result = mysqli_query($conn, $query);
    
    // التحقق من وجود نتيجة
    if (mysqli_num_rows($result) == 1) {
        // إذا كانت البيانات صحيحة
        
        // الحصول على بيانات المسؤول
        $admin = mysqli_fetch_assoc($result);
        
        // حفظ معلومات تسجيل الدخول في Session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        
        // التوجيه إلى لوحة التحكم
        header('Location: dashboard.php');
        exit();
        
    } else {
        // إذا كانت البيانات خاطئة
        $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
    }
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- عنوان الصفحة -->
    <title>تسجيل الدخول - لوحة التحكم</title>
    
    <!-- ربط ملف التنسيق CSS الرئيسي -->
    <link rel="stylesheet" href="../css/style.css">
    
    <!-- خط عربي جميل من Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* تنسيقات خاصة بصفحة تسجيل الدخول */
        body {
            background: linear-gradient(135deg, #8d6e63 0%, #6d4c41 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #6d4c41;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #999;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #6d4c41;
            font-weight: 600;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-family: 'Cairo', sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #ff8a65;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-right: 4px solid #c62828;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ff8a65 0%, #ff7043 100%);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 138, 101, 0.4);
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #8d6e63;
            text-decoration: none;
            font-size: 0.95rem;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- حاوية صفحة تسجيل الدخول -->
    <div class="login-container">
        
        <!-- رأس صفحة تسجيل الدخول -->
        <div class="login-header">
            <h1>🔐 لوحة التحكم</h1>
            <p>لقمة جدتي - تسجيل الدخول</p>
        </div>

        <!-- عرض رسالة الخطأ إن وجدت -->
        <?php if ($error): ?>
            <div class="error-message">
                ⚠️ <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- نموذج تسجيل الدخول -->
        <form method="POST" action="">
            
            <!-- حقل اسم المستخدم -->
            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="أدخل اسم المستخدم"
                    required 
                    autofocus>
            </div>

            <!-- حقل كلمة المرور -->
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="أدخل كلمة المرور"
                    required>
            </div>

            <!-- زر تسجيل الدخول -->
            <button type="submit" class="btn-login">
                دخول
            </button>

        </form>

        <!-- رابط العودة للموقع -->
        <div class="back-link">
            <a href="../index.php">← العودة للموقع الرئيسي</a>
        </div>

    </div>

</body>
</html>
<?php
// إغلاق الاتصال بقاعدة البيانات
mysqli_close($conn);
?>
