<?php
// controllers/DoctorController.php

require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/helpers.php';

class DoctorController {
    private $appointmentModel;

    public function __construct() {
        // إذا لم يكن مسجلاً دخوله أو لم يكن طبيباً، يتم توجيهه لصفحة تسجيل الدخول فوراً
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            redirect('auth/login');
        }
        $this->appointmentModel = new AppointmentModel();
    }

    // عرض اللوحة الرئيسية للطبيب وجدول المواعيد الخاصة به
    public function index() {
        $currentUser = $_SESSION['user'];
        
        // جلب جميع مواعيد العيادة
        $appointments = $this->appointmentModel->getAll();

        $pageTitle = "لوحة تحكم الطبيب";
        require_once __DIR__ . '/../views/doctor/dashboard.php';
    }

    // تحديث حالة الموعد (قبول أو إلغاء) من قبل الطبيب
    public function updateAppointmentStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('doctor/dashboard');
        }

        $appointmentId = intval($_POST['appointment_id'] ?? 0);
        $status = sanitize($_POST['status'] ?? '');

        // التحقق من صحة الحالة المرسلة (مقبول أو ملغي)
        if ($appointmentId > 0 && in_array($status, ['approved', 'canceled'])) {
            $result = $this->appointmentModel->updateStatus($appointmentId, $status);
            if ($result) {
                flash('success', 'تم تحديث حالة الموعد بنجاح.');
            } else {
                flash('error', 'حدث خطأ أثناء محاولة تحديث الحالة، يرجى المحاولة مجدداً.');
            }
        } else {
            flash('error', 'بيانات الطلب غير صالحة.');
        }

        redirect('doctor/dashboard');
    }
}