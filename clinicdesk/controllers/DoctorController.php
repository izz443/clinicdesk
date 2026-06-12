<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/SpecializationModel.php';

class DoctorController {
    private $userModel;
    private $doctorModel;
    private $specializationModel;

    public function __construct() {
        Auth::requireRole('admin');
        $this->userModel = new UserModel();
        $this->doctorModel = new DoctorModel();
        $this->specializationModel = new SpecializationModel();
    }

    public function index() {
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $totalItems = $this->doctorModel->countAll();

        $paginator = new Paginator($totalItems, ITEMS_PER_PAGE, $currentPage);
        $doctors = $this->doctorModel->getAllPaginated($paginator->totalPages() >= $currentPage ? ITEMS_PER_PAGE : $totalItems, $paginator->offset());

        include __DIR__ . '/../views/doctors/index.php';
    }

    public function create() {
        $errors = [];
        $specializations = $this->specializationModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !CSRF::validateToken($_POST['csrf_token'])) {
                die("CSRF Token Invalid");
            }

            $name = sanitize($_POST['name']);
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];
            $phone = sanitize($_POST['phone']);
            $specialization_id = (int)$_POST['specialization_id'];
            $consultation_fee = (float)$_POST['consultation_fee'];
            $bio = sanitize($_POST['bio']);
            $available_days = isset($_POST['days']) ? implode(',', $_POST['days']) : 'Sun,Mon,Tue,Wed,Thu';

            if (empty($name) || empty($email) || empty($password) || !$specialization_id) {
                $errors[] = "الاسم، البريد، كلمة المرور، والتخصص الطبي حقول إجبارية.";
            }

            if ($this->userModel->getByEmail($email)) {
                $errors[] = "البريد الإلكتروني مسجل لطبيب أو مستخدم آخر.";
            }

            $avatarPath = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                if (in_array($_FILES['avatar']['type'], ALLOWED_IMAGE_TYPES) && $_FILES['avatar']['size'] <= MAX_FILE_SIZE) {
                    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                    $fileName = bin2hex(random_bytes(16)) . '.' . $ext;
                    move_uploaded_file($_FILES['avatar']['tmp_name'], __DIR__ . '/../public/uploads/avatars/' . $fileName);
                    $avatarPath = 'uploads/avatars/' . $fileName;
                }
            }

            if (empty($errors)) {
                $userData = [
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
                    'role' => 'doctor',
                    'phone' => $phone,
                    'avatar' => $avatarPath,
                    'is_active' => 1
                ];

                $userId = $this->userModel->create($userData);
                if ($userId) {
                    $doctorData = [
                        'user_id' => $userId,
                        'specialization_id' => $specialization_id,
                        'bio' => $bio,
                        'consultation_fee' => $consultation_fee,
                        'available_days' => $available_days
                    ];
                    $this->doctorModel->create($doctorData);
                    set_flash_message('success', 'تم تسجيل الطبيب وإنشاء ملفه المهني بنجاح.');
                    redirect('index.php?page=doctors&action=index');
                }
            }
        }
        include __DIR__ . '/../views/doctors/create.php';
    }
}
