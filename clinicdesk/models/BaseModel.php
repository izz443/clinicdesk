<?php
abstract class BaseModel {
    protected $db;
    protected $conn;

    public function __construct() {
        // جلب نسخة السنجلتون الثابتة للاتصال بقاعدة البيانات
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
}
