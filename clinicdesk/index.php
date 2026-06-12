<?php
// بدء الجلسة الآمنة في النظام كاملاً
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// استدعاء ملفات التكوين والبرمجيات المساعدة الثابتة
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/helpers.php';

// استيراد الكلاسات الأساسية تلقائياً أو يدوياً لضمان العمل
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/CSRF.php';
require_once __DIR__ . '/core/Paginator.php';

// جلب الصفحة والحدث الافتراضي من الرابط
$page = isset($_GET['page']) ? sanitize($_GET['page']) : 'dashboard';
$action = isset($_GET['action']) ? sanitize($_GET['action']) : 'index';

// التوجيه المركزي (Routing Logic) بناءً على الكنترولر المطلوب
switch ($page) {
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        break;
        
    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        $controller = new DashboardController();
        break;
        
    case 'users':
        require_once __DIR__ . '/controllers/UserController.php';
        $controller = new UserController();
        break;
        
    case 'doctors':
        require_once __DIR__ . '/controllers/DoctorController.php';
        $controller = new DoctorController();
        break;
        
    case 'appointments':
        require_once __DIR__ . '/controllers/AppointmentController.php';
        $controller = new AppointmentController();
        break;
        
    case 'prescriptions':
        require_once __DIR__ . '/controllers/PrescriptionController.php';
        $controller = new PrescriptionController();
        break;
        
    case 'reports':
        require_once __DIR__ . '/controllers/ReportController.php';
        $controller = new ReportController();
        break;
        
    default:
        http_response_code(404);
        include __DIR__ . '/views/errors/404.php';
        exit();
}

// تشغيل الأكشن المطلوب داخل الكنترولر المستدعى
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    http_response_code(404);
    include __DIR__ . '/views/errors/404.php';
    exit();
}
