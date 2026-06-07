<?php
// controllers/PatientController.php

require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/UserModel.php'; // الاعتماد على UserModel كبديل آمن
require_once __DIR__ . '/../models/SpecializationModel.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/helpers.php';

class PatientController {
    private $appointmentModel;
    private $userModel;
    private $specializationModel;

    public function __construct() {
        // إذا لم يكن مسجلاً دخوله أو لم يكن مريضاً، يتم توجيهه لصفحة تسجيل الدخول فوراً
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'patient') {
            redirect('auth/login');
        }
        $this->appointmentModel = new AppointmentModel();
        $this->userModel = new UserModel(); // جلب كائن المستخدمين
        $this->specializationModel = new SpecializationModel();
    }

    // عرض اللوحة الرئيسية للمريض (جدول المواعيد ونموذج الحجز)
    public function index() {
        $currentUser = $_SESSION['user'];
        
        // جلب البيانات اللازمة لتعبئة الحقول والجدول بالواجهة
        $appointments = $this->appointmentModel->getAll();
        
        // جلب الأطباء من جدول المستخدمين مباشرة لمنع خطأ كلاس DoctorModel
        $doctors = $this->userModel->getAllByRole('doctor');
        $specializations = $this->specializationModel->getAll();

        $pageTitle = "لوحة تحكم المريض";
        require_once __DIR__ . '/../views/patient/dashboard.php';
    }

    // معالجة طلب حجز موعد جديد
    public function bookAppointment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('patient/dashboard');
        }

        $patientId = $_SESSION['user']['id'];
        $doctorId = intval($_POST['doctor_id'] ?? 0);
        $specId = intval($_POST['specialization_id'] ?? 0);
        $date = sanitize($_POST['appointment_date'] ?? '');
        $time = sanitize($_POST['appointment_time'] ?? '');
        $notes = sanitize($_POST['notes'] ?? '');

        if ($doctorId > 0 && $specId > 0 && !empty($date) && !empty($time)) {
            $result = $this->appointmentModel->create($patientId, $doctorId, $specId, $date, $time, $notes);
            if ($result) {
                flash('success', 'تم إرسال طلب حجز الموعد بنجاح وهو قيد المراجعة حالياً.');
            } else {
                flash('error', 'حدث خطأ أثناء محاولة حفظ الموعد، يرجى المحاولة مجدداً.');
            }
        } else {
            flash('error', 'يرجى تعبئة جميع الحقول المطلوبة بشكل صحيح.');
        }

        redirect('patient/dashboard');
    }
}