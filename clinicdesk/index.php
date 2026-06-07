<?php
// index.php - الملف الشامل والمطابق لجميع شروط المرفق والتنقل بين اللوحات

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. استدعاء ملفات النواة والإعدادات
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/helpers.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/CSRF.php';

// 2. تحديث باسورود الآدمن لضمان الدخول المضمون
try {
    $db = Database::getInstance();
    $new_hash = password_hash('Admin@1234', PASSWORD_BCRYPT);
    $db->query("UPDATE users SET password = ? WHERE email = 'admin@clinic.local'", "s", [$new_hash]);
} catch (Exception $e) {
    // يتخطى إذا كانت هناك مشكلة بقاعدة البيانات لعدم كسر الصفحة
}

// 3. استدعاء الموديلات البرمجية بالكامل لتشغيل كل الأيقونات
require_once __DIR__ . '/models/BaseModel.php';
require_once __DIR__ . '/models/DoctorModel.php';
require_once __DIR__ . '/models/AppointmentModel.php';
require_once __DIR__ . '/models/SpecializationModel.php';

// 4. بدء الجلسة الأمنية
Auth::init();

$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

// 5. جدار الحماية (Login Wall)
if (!Auth::check() && $page !== 'auth/login') {
    redirect('auth/login');
}

// 6. نظام التوجيه الموحد (Routing) لتشغيل جميع الأزرار والأيقونات
switch ($page) {
    case 'auth/login':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        if ($action === 'submit') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case 'auth/logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'specializations':
        require_once __DIR__ . '/controllers/SpecializationController.php';
        $controller = new SpecializationController();
        if ($action === 'create') {
            $controller->create();
        } elseif ($action === 'delete') {
            $controller->delete();
        } else {
            $controller->index();
        }
        break;

    case 'users':
        require_once __DIR__ . '/controllers/UserController.php';
        $controller = new UserController();
        if ($action === 'toggle-status') {
            $controller->toggleStatus();
        } else {
            $controller->index();
        }
        break;

    // تشغيل أيقونات وأزرار لوحة تحكم الطبيب
    case 'doctor/dashboard':
        require_once __DIR__ . '/controllers/DoctorController.php';
        $controller = new DoctorController();
        if ($action === 'update-status') {
            $controller->updateAppointmentStatus();
        } else {
            $controller->index();
        }
        break;

    // تشغيل أيقونات وأزرار لوحة تحكم المريض وحجز المواعيد
    case 'patient/dashboard':
        require_once __DIR__ . '/controllers/PatientController.php';
        $controller = new PatientController();
        if ($action === 'book') {
            $controller->bookAppointment();
        } else {
            $controller->index();
        }
        break;

    default:
        require_once __DIR__ . '/views/errors/404.php';
        break;
}