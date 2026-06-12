<?php
class Database {
    private static $instance = null;
    private $connection;

    // منع الإنشاء المباشر من خارج الكلاس
    private function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->connection->connect_error) {
            die("فشل الاتصال بقاعدة البيانات: " . $this->connection->connect_error);
        }
        
        $this->connection->set_charset("utf8mb4");
    }

    // منع نسخ الكائن لضمان السنجلتون
    private function __clone() {}

    // استرجاع النسخة الوحيدة الثابتة للاتصال
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // الحصول على كائن الاتصال الفعلي بالـ mysqli
    public function getConnection() {
        return $this->connection;
    }

    // دالة مساعدة لتسهيل تنفيذ الاستعلامات الآمنة (Prepared Statements)
    public function query($sql, $types = "", $params = []) {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            die("خطأ في تجهيز الاستعلام الممرر: " . $this->connection->error);
        }

        if (!empty($types) && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            die("خطأ أثناء تنفيذ الاستعلام: " . $stmt->error);
        }

        return $stmt;
    }
}
