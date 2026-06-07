<?php
// models/SpecializationModel.php

require_once __DIR__ . '/BaseModel.php';

class SpecializationModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $sql = "SELECT * FROM specializations ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function create($name) {
        $sql = "INSERT INTO specializations (name) VALUES (?)";
        return $this->db->query($sql, "s", [$name]);
    }

    public function delete($id) {
        $sql = "DELETE FROM specializations WHERE id = ?";
        return $this->db->query($sql, "i", [$id]);
    }

    // دالة حساب إجمالي التخصصات للداشبورد
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM specializations";
        $stmt = $this->db->query($sql);
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }
}