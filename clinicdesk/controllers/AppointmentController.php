<?php
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/SpecializationModel.php';

class AppointmentController {
    private $appointmentModel;
    private $doctorModel;
    private $specializationModel;

    public function __construct() {
        if (!Auth::check()) {
            redirect('index.php?page=auth&action=login');
        }
        $this->appointmentModel = new AppointmentModel();
        $this->doctorModel = new DoctorModel();
        $this->specializationModel = new SpecializationModel();
    }

    public function index() {
        $user = Auth::currentUser();
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        
        $roleId = $user['id'];
        if ($user['role'] === 'doctor') {
            $doc = $this->doctorModel->getByUserId($user['id']);
            $roleId = $doc ? $doc['id'] : 0;
        }

        $totalItems = $this->appointmentModel->countAll($user['role'], $roleId);
        $paginator = new Paginator($totalItems, ITEMS_PER_PAGE, $currentPage);
        
        $appointments = $this->appointmentModel->getAllPaginated(
            $user['role'], 
            $roleId, 
            $paginator->totalPages() >= $currentPage ? ITEMS_PER_PAGE : $totalItems, 
            $paginator->offset()
        );

        include __DIR__ . '/../views/appointments/index.php';
    }

    public function create() {
        Auth::requireRole('patient');
        $errors = [];
        
        $specializations = $this->specializationModel->getAll();
        $selected_specialization = isset($_GET['specialization_id']) ? (int)$_GET['specialization_id'] : 0;
        $doctors = $selected_specialization ? $this->doctorModel->getBySpecialization($selected_specialization) : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !CSRF::validateToken($_POST['csrf_token'])) {
                die("CSRF Token Invalid");
            }

            $doctor_id = (int)$_POST['doctor_id'];
            $appt_date = sanitize($_POST['appt_date']);
            $appt_time = sanitize($_POST['appt_time']);
            $reason = sanitize($_POST['reason']);

            if (!$doctor_id || empty($appt_date) || empty($appt_time)) {
                $errors[] = "جميع حقول الحجز إجبارية الاختيار.";
            }

            // منع الحجز المزدوج في نفس الساعة واليوم للطبيب المحدد
            if ($this->appointmentModel->isDoubleBooked($doctor_id, $appt_date, $appt_time)) {
                $errors[] = "الطبيب غير متاح في هذا الوقت والتاريخ المحدد، تم حجزه مسبقاً.";
            }

            if (empty($errors)) {
                $data = [
                    'patient_id' => Auth::currentUser()['id'],
                    'doctor_id' => $doctor_id,
                    'appt_date' => $appt_date,
                    'appt_time' => $appt_time,
                    'reason' => $reason
                ];

                if ($this->appointmentModel->create($data)) {
                    set_flash_message('success', 'تم إرسال طلب حجز الموعد بنجاح وهو قيد الانتظار.');
                    redirect('index.php?page=appointments&action=index');
                }
            }
        }
        include __DIR__ . '/../views/appointments/create.php';
    }

    public function updateStatus() {
        Auth::requireRole('admin', 'doctor');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $status = isset($_GET['status']) ? sanitize($_GET['status']) : '';
        
        $appointment = $this->appointmentModel->getById($id);
        if (!$appointment) {
            redirect('index.php?page=appointments&action=index');
        }

        // فحص ملكية الطبيب للموعد (Ownership Check) لمنع التلاعب برقم الـ ID من طبيب آخر
        if (Auth::role() === 'doctor') {
            $doctor = $this->doctorModel->getByUserId(Auth::currentUser()['id']);
            if ($appointment['doctor_id'] != $doctor['id']) {
                http_response_code(403);
                include __DIR__ . '/../views/errors/403.php';
                exit();
            }
        }

        if (in_array($status, ['confirmed', 'completed', 'cancelled'])) {
            $notes = isset($_POST['doctor_notes']) ? sanitize($_POST['doctor_notes']) : $appointment['doctor_notes'];
            $this->appointmentModel->updateStatus($id, $status, $notes);
            set_flash_message('success', 'تم تحديث حالة الموعد بنجاح.');
        }

        redirect('index.php?page=appointments&action=index');
    }
}
