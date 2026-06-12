<?php
require_once __DIR__ . '/../models/PrescriptionModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';

class PrescriptionController {
    private $prescriptionModel;
    private $appointmentModel;
    private $doctorModel;

    public function __construct() {
        if (!Auth::check()) {
            redirect('index.php?page=auth&action=login');
        }
        $this->prescriptionModel = new PrescriptionModel();
        $this->appointmentModel = new AppointmentModel();
        $this->doctorModel = new DoctorModel();
    }

    public function create() {
        Auth::requireRole('doctor');
        $appointment_id = isset($_GET['appointment_id']) ? (int)$_GET['appointment_id'] : 0;
        $appointment = $this->appointmentModel->getById($appointment_id);

        if (!$appointment || $appointment['status'] !== 'confirmed') {
            set_flash_message('danger', 'لا يمكن إصدار روشتة لموعد غير مؤكد.');
            redirect('index.php?page=appointments&action=index');
        }

        $doctor = $this->doctorModel->getByUserId(Auth::currentUser()['id']);
        if ($appointment['doctor_id'] != $doctor['id']) {
            die("غير مصرح لك بإصدار روشتة لهذا الموعد.");
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !CSRF::validateToken($_POST['csrf_token'])) {
                die("CSRF Token Invalid");
            }

            $diagnosis = sanitize($_POST['diagnosis']);
            $medications = sanitize($_POST['medications']);
            $notes = sanitize($_POST['notes']);

            if (empty($diagnosis) || empty($medications)) {
                $errors[] = "التشخيص والوصفة الدوائية حقول إجبارية للروشتة.";
            }

            $filePath = null;
            if (isset($_FILES['prescription_file']) && $_FILES['prescription_file']['error'] === UPLOAD_ERR_OK) {
                if ($_FILES['prescription_file']['type'] === 'application/pdf' && $_FILES['prescription_file']['size'] <= 2097152) {
                    $fileName = 'presc_' . bin2hex(random_bytes(10)) . '.pdf';
                    move_uploaded_file($_FILES['prescription_file']['tmp_name'], __DIR__ . '/../public/uploads/prescriptions/' . $fileName);
                    $filePath = 'uploads/prescriptions/' . $fileName;
                } else {
                    $errors[] = "يجب رفع ملف الروشتة بصيغة PDF فقط ولا يتعدى 2 ميجابايت.";
                }
            }

            if (empty($errors)) {
                $data = [
                    'appointment_id' => $appointment_id,
                    'diagnosis' => $diagnosis,
                    'medications' => $medications,
                    'notes' => $notes,
                    'file_path' => $filePath
                ];

                if ($this->prescriptionModel->create($data)) {
                    $this->appointmentModel->updateStatus($appointment_id, 'completed', $appointment['doctor_notes']);
                    set_flash_message('success', 'تم حفظ الروشتة وإكمال الكشف بنجاح.');
                    redirect('index.php?page=appointments&action=index');
                }
            }
        }
        include __DIR__ . '/../views/prescriptions/create.php';
    }

    // تحميل وقراءة الروشتة بأمان فائق وحصري للملاك والمعالجين والمسؤولين
    public function download() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $prescription = $this->prescriptionModel->getById($id);

        if (!$prescription) {
            die("الملف أو الروشتة غير موجودة في النظام.");
        }

        $currentUser = Auth::currentUser();
        
        // فحص قيود الملكية التامة (Ownership Enforcement) لمنع المرضى أو الأطباء من التجسس
        if ($currentUser['role'] === 'patient' && $prescription['patient_id'] != $currentUser['id']) {
            http_response_code(403);
            include __DIR__ . '/../views/errors/403.php';
            exit();
        }
        
        if ($currentUser['role'] === 'doctor') {
            $doctor = $this->doctorModel->getByUserId($currentUser['id']);
            if ($prescription['doctor_id'] != $doctor['id']) {
                http_response_code(403);
                include __DIR__ . '/../views/errors/403.php';
                exit();
            }
        }

        // المسؤول الإداري (Admin) مقيد حسب تعليمات الواجب الصارمة من قراءة ملفات الروشتات بشكل مباشر
        if ($currentUser['role'] === 'admin') {
            die("غير مسموح للمسؤول الإداري بفتح وقراءة مستند الروشتة المباشر حماية لخصوصية المريض.");
        }

        $fullPath = __DIR__ . '/../public/' . $prescription['file_path'];
        if (!empty($prescription['file_path']) && file_exists($fullPath)) {
            // إرسال الهيدرز لتشغيل السيل بأمان ودون إظهار المسار الحقيقي للمتصفح
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="Prescription_' . $id . '.pdf"');
            header('Content-Length: ' . filesize($fullPath));
            readfile($fullPath);
            exit();
        } else {
            die("لم يتم رفع ملف PDF ملحق مع هذه الروشتة من قبل الطبيب، التشخيص مسجل نصياً فقط.");
        }
    }
}
