<?php
// controllers/UserController.php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';

class UserController {
    private $userModel;

    public function __construct() {
        // حماية أمنية: يمنع دخول هذا القسم لغير مسؤول النظام (Admin)
        Auth::requireRole('admin');
        $this->userModel = new UserModel();
    }

    // عرض قائمة الأطباء والمرضى في النظام
    public function index() {
        $pageTitle = "إدارة مستخدمي النظام";
        
        // جلب البيانات من خلال الموديل
        $doctors = $this->userModel->getAllByRole('doctor');
        $patients = $this->userModel->getAllByRole('patient');
        
        require_once __DIR__ . '/../views/users/index.php';
    }

    // عكس حالة المستخدم (تنشيط / تجميد)
    public function toggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('users');
        }

        // التحقق من توكن الحماية لمنع الثغرات
        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            flash('error', 'فشل التحقق من رمز الحماية CSRF.');
            redirect('users');
        }

        $userId = (int)($_POST['id'] ?? 0);
        $currentStatus = (int)($_POST['is_active'] ?? 0);
        
        // تبديل الحالة: إذا كان 1 يصبح 0، وإذا كان 0 يصبح 1
        $newStatus = ($currentStatus === 1) ? 0 : 1;

        if ($this->userModel->updateStatus($userId, $newStatus)) {
            flash('success', 'تم تحديث حالة الحساب بنجاح.');
        } else {
            flash('error', 'فشل تحديث حالة حساب المستخدم.');
        }

        redirect('users');
    }
}