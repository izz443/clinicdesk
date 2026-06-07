<?php
// models/BaseModel.php

require_once __DIR__ . '/../core/Database.php';

abstract class BaseModel {
    protected $db;

    public function __construct() {
        // جلب نسخة الاتصال الوحيدة الموحدة
        $this->db = Database::getInstance();
    }

    // دالة مساعدة لتنفيذ الاستعلامات المجهزة المكررة بأمان
    protected function execute($sql, $types = "", $params = []) {
        $stmt = $this->db->query($sql, $types, $params);
        if (!$stmt) {
            error_log("Query failed: " . $sql);
            return false;
        }
        return $stmt;
    }
}