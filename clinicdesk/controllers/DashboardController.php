<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';

class DashboardController {
    private $userModel;
    private $doctorModel;
    private $appointmentModel;

    public function __construct() {
        // فرض حماية عامة: يجب أن يكون مسجلاً للدخول لرؤية أي لوحة تحكم
        if (!Auth::check()) {
            redirect('index.php?page=auth&action=login');
        }
        $this->userModel = new UserModel();
        $this->doctorModel = new DoctorModel();
        $this->appointmentModel = new AppointmentModel();
    }

    public function index() {
        $user = Auth::currentUser();
        $role = $user['role'];

        if ($role === 'admin') {
            // جلب إحصائيات الأدمن
            $stats = [
                'total_patients' => $this->userModel->countAll('patient'),
                'total_doctors' => $this->doctorModel->countAll(),
                'total_appointments' => $this->appointmentModel->countAll('admin', null)
            ];
            include __DIR__ . '/../views/dashboard/admin.php';
        } 
        elseif ($role === 'doctor') {
            // جلب بيانات الطبيب ومواعيده الخاصة
            $doctorData = $this->doctorModel->getByUserId($user['id']);
            $stats = [
                'total_appointments' => $doctorData ? $this->appointmentModel->countAll('doctor', $doctorData['id']) : 0
            ];
            include __DIR__ . '/../views/dashboard/doctor.php';
        } 
        elseif ($role === 'patient') {
            // جلب مواعيد المريض الخاصة به
            $stats = [
                'total_appointments' => $this->appointmentModel->countAll('patient', $user['id'])
            ];
            include __DIR__ . '/../views/dashboard/patient.php';
        } 
        else {
            Auth::logout();
        }
    }
}
