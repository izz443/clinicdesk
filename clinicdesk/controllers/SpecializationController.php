<?php
// controllers/SpecializationController.php

require_once __DIR__ . '/../models/SpecializationModel.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';

class SpecializationController {
    private $specModel;

    public function __construct() {
        // حماية كاملة: منع الأطباء والمرضى من دخول هذا القسم نهائياً
        Auth::requireRole('admin');
        $this->specModel = new SpecializationModel();
    }

    // عرض قائمة التخصصات
    public function index() {
        $pageTitle = "إدارة التخصصات الطبية";
        $specializations = $this->specModel->getAll();
        require_once __DIR__ . '/../views/specializations/index.php';
    }

    // معالجة إضافة تخصص جديد
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('specializations');
        }

        // التحقق من توكن الحماية CSRF
        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            flash('error', 'فشل التحقق من رمز الحماية CSRF.');
            redirect('specializations');
        }

        $name = sanitize($_POST['name'] ?? '');

        if (empty($name)) {
            flash('error', 'اسم التخصص مطلوب ولا يمكن أن يكون فارغاً.');
            redirect('specializations');
        }

        if ($this->specModel->create($name)) {
            flash('success', 'تم إضافة التخصص الطبي بنجاح.');
        } else {
            flash('error', 'فشل إضافة التخصص، قد يكون الاسم مكرراً.');
        }

        redirect('specializations');
    }

    // معالجة حذف تخصص
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('specializations');
        }

        // التحقق من توكن الحماية CSRF
        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            flash('error', 'فشل التحقق من رمز الحماية CSRF.');
            redirect('specializations');
        }

        $id = (int)($_POST['id'] ?? 0);

        // محاولة الحذف (ستفشل تلقائياً بفضل قيود الـ DB إذا كان هناك طبيب مسجل في هذا التخصص)
        try {
            if ($this->specModel->delete($id)) {
                flash('success', 'تم حذف التخصص بنجاح.');
            } else {
                flash('error', 'لا يمكن حذف هذا التخصص لارتباطه بأطباء مسجلين.');
            }
        } catch (Exception $e) {
            flash('error', 'خطأ: لا يمكن حذف التخصص لوجود أطباء يعملون به حالياً.');
        }

        redirect('specializations');
    }
}