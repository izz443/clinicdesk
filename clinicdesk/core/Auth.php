<?php
// core/Auth.php

class Auth {
    // بدء الجلسة بأمان
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            // إعدادات أمان إضافية للملفات التعريفية للجلسة (Cookies)
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            session_start();
        }
    }

    // تسجيل دخول المستخدم وحفظ بياناته في الجلسة
    public static function login($user) {
        self::init();
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
            'email' => $user['email']
        ];
        // توليد معرف جلسة جديد لحماية المستخدم من هجمات Session Fixation
        session_regenerate_id(true);
    }

    // تسجيل الخروج وتدمير الجلسة
    public static function logout() {
        self::init();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    // التحقق هل المستخدم سجل دخوله أم لا
    public static function check() {
        self::init();
        return isset($_SESSION['user']);
    }

    // جلب بيانات المستخدم الحالي
    public static function currentUser() {
        self::init();
        return $_SESSION['user'] ?? null;
    }

    // جلب صلاحية المستخدم الحالي
    public static function role() {
        self::init();
        return $_SESSION['user']['role'] ?? '';
    }

    // فرض حماية الرتب (لو حاول مريض دخول صفحة طبيب يتم تحويله لصفحة خطأ)
    public static function requireRole(...$roles) {
        self::init();
        if (!self::check()) {
            header("Location: " . BASE_URL . "index.php?page=auth/login");
            exit();
        }
        if (!in_array(self::role(), $roles)) {
            header("Location: " . BASE_URL . "index.php?page=errors/403");
            exit();
        }
    }
}