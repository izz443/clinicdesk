<?php
// core/helpers.php

// دالة تنظيف البيانات لمنع هجمات XSS
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// دالة التوجيه السريع بين الصفحات
function redirect($page) {
    header("Location: " . BASE_URL . "index.php?page=" . $page);
    exit();
}

// دالة إدارة رسائل التنبيه المؤقتة (تظهر لمرة واحدة وتختفي عند تحديث الصفحة)
function flash($key, $message = "") {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!empty($message)) {
        $_SESSION['flash'][$key] = $message;
    } elseif (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}