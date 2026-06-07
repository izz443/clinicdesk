<?php
// core/CSRF.php

class CSRF {
    // توليد رمز حماية فريد وحفظه في الجلسة
    public static function generateToken() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // التحقق من صحة الرمز المرسل من النموذج
    public static function validateToken($token) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        // مقارنة آمنة تمنع هجمات التوقيت (Timing Attacks)
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}