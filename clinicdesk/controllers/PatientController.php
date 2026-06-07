<?php
// controllers/PatientController.php

require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/SpecializationModel.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/helpers.php';

class PatientController {
    private $appointmentModel;
    private $doctorModel;
    private $specializationModel;

    public function __construct() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'patient') {
            redirect('auth/login');
        }
        $this->appointmentModel = new AppointmentModel();
        $this->doctorModel = new DoctorModel();
        $this->specializationModel = new SpecializationModel();
    }

    public function index() {
        $currentUser = $_SESSION['user'];
        $appointments = $this->appointmentModel->getAll();
        
        // جلب الأطباء باستخدام الموديل الأساسي الموجود عندك
        $doctors = [];
        if (method_exists($this->doctorModel, 'getAllByRole')) {
            $doctors = $this->doctorModel->getAllByRole('doctor');
        } elseif (method_exists($this->doctorModel, 'getAll')) {
            $doctors = $this->doctorModel->getAll();
        }

        $specializations = $this->specializationModel->getAll();

        $pageTitle = "لوحة تحكم المريض";
        require_once __DIR__ . '/../views/patient/dashboard.php';
    }

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
                flash('success', 'تم إرسال طلب حجز الموعد بنجاح.');
            } else {
                flash('error', 'حدث خطأ أثناء محاولة حفظ الموعد.');
            }
        } else {
            flash('error', 'يرجى تعبئة جميع الحقول المطلوبة.');
        }

        redirect('patient/dashboard');
    }
}