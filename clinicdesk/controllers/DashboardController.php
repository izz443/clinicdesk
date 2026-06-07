<?php
// controllers/DashboardController.php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/SpecializationModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/helpers.php';

class DashboardController {

    public function __construct() {
        // التعديل هنا: استخدام دالة check المتوافقة مع نظامك لمنع الخطأ
        if (!Auth::check()) {
            redirect('auth/login');
        }
    }

    public function index() {
        $pageTitle = "لوحة التحكم الرئيسية";
        
        $userModel = new UserModel();
        $specModel = new SpecializationModel();
        $appointmentModel = new AppointmentModel();

        // جلب الإحصائيات الحقيقية من قاعدة البيانات
        $totalDoctors = $userModel->countAll('doctor');
        $totalPatients = $userModel->countAll('patient');
        $totalSpecializations = $specModel->countAll();
        $totalAppointments = $appointmentModel->countAll();

        require_once __DIR__ . '/../views/dashboard/admin.php';
    }
}