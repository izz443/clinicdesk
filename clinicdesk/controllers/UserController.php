<?php
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        // حظر الدخول لغير المسؤول الإداري
        Auth::requireRole('admin');
        $this->userModel = new UserModel();
    }

    // عرض قائمة المرضى مع الـ Pagination
    public function index() {
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $totalItems = $this->userModel->countAll('patient');
        
        $paginator = new Paginator($totalItems, ITEMS_PER_PAGE, $currentPage);
        $users = $this->userModel->getAllPaginated($paginator->totalPages() >= $currentPage ? ITEMS_PER_PAGE : $totalItems, $paginator->offset(), 'patient');

        include __DIR__ . '/../views/users/index.php';
    }

    // إنشاء مستخدم/مريض جديد
    public function create() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !CSRF::validateToken($_POST['csrf_token'])) {
                die("CSRF Token Invalid");
            }

            $name = sanitize($_POST['name']);
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];
            $phone = sanitize($_POST['phone']);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($name) || empty($email) || empty($password)) {
                $errors[] = "الاسم والبريد الإلكتروني وكلمة المرور حقول إجبارية.";
            }

            if ($this->userModel->getByEmail($email)) {
                $errors[] = "البريد الإلكتروني مستخدم مسبقاً في النظام.";
            }

            $avatarPath = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $fileType = $_FILES['avatar']['type'];
                $fileSize = $_FILES['avatar']['size'];
                
                if (!in_array($fileType, ALLOWED_IMAGE_TYPES)) {
                    $errors[] = "نوع الصورة غير مسموح. يسمح فقط بصيغ JPG و PNG.";
                }
                if ($fileSize > MAX_FILE_SIZE) {
                    $errors[] = "حجم الصورة يتجاوز الحد المسموح به (1 ميجا).";
                }

                if (empty($errors)) {
                    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                    $fileName = bin2hex(random_bytes(16)) . '.' . $ext;
                    $targetDir = __DIR__ . '/../public/uploads/avatars/';
                    
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }
                    
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetDir . $fileName)) {
                        $avatarPath = 'uploads/avatars/' . $fileName;
                    }
                }
            }

            if (empty($errors)) {
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
                    'role' => 'patient',
                    'phone' => $phone,
                    'avatar' => $avatarPath,
                    'is_active' => $is_active
                ];

                if ($this->userModel->create($data)) {
                    set_flash_message('success', 'تم إضافة حساب المريض بنجاح.');
                    redirect('index.php?page=users&action=index');
                } else {
                    $errors[] = "حدث خطأ أثناء حفظ البيانات بقاعدة البيانات.";
                }
            }
        }
        include __DIR__ . '/../views/users/create.php';
    }

    // تبديل حالة الحساب (تفعيل/تعطيل) فورا
    public function toggle() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $user = $this->userModel->getById($id);
        if ($user) {
            $newStatus = (int)$user['is_active'] === 1 ? 0 : 1;
            $this->userModel->toggleStatus($id, $newStatus);
            set_flash_message('success', 'تم تعديل حالة الحساب بنجاح.');
        }
        redirect('index.php?page=users&action=index');
    }

    // حذف حساب المستخدم
    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $user = $this->userModel->getById($id);
        if ($user) {
            if ($user['avatar'] && file_exists(__DIR__ . '/../public/' . $user['avatar'])) {
                unlink(__DIR__ . '/../public/' . $user['avatar']);
            }
            $this->userModel->delete($id);
            set_flash_message('success', 'تم حذف حساب المريض بنجاح.');
        }
        redirect('index.php?page=users&action=index');
    }
}
