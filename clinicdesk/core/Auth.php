<?php
class Auth {
    // تسجيل الدخول وتخزين بيانات المستخدم في جلسة آمنة مع تجديد معرف الجلسة
    public static function login($user) {
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        session_regenerate_id(true); // حماية ضد Session Fixation
    }

    // تسجيل الخروج التام وتدمير الجلسة
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_unset();
        session_destroy();
        redirect('index.php?page=auth&action=login');
    }

    // التحقق من أن المستخدم مسجل في النظام حالياً
    public static function check() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    // جلب مصفوفة بيانات المستخدم الحالي
    public static function currentUser() {
        return self::check() ? $_SESSION['user'] : null;
    }

    // جلب دور الصلاحية للمستخدم الحالي
    public static function role() {
        return self::check() ? $_SESSION['user']['role'] : '';
    }

    // فرض قيود الرتب والأدوار على مستوى الكنترولر والصفحات
    public static function requireRole(...$roles) {
        if (!self::check()) {
            redirect('index.php?page=auth&action=login');
        }
        
        if (!in_array(self::role(), $roles)) {
            http_response_code(403);
            include __DIR__ . '/../views/errors/403.php';
            exit();
        }
    }
}
