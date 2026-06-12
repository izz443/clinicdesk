<?php
// إعادة التوجيه الفوري
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// حماية وتطهير البيانات لمنع ثغرات XSS وحقن النصوص
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// تعيين رسائل الفلاش المؤقتة للجلسات
function set_flash_message($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

// تنسيق التواريخ بشكل مقروء ونظيف
function formatDate($date) {
    return date("Y-m-d", strtotime($date));
}

// تنسيق الوقت بالشكل المدني أو القياسي السهل
function formatTime($time) {
    return date("h:i A", strtotime($time));
}
