<?php
// models/AppointmentModel.php

require_once __DIR__ . '/BaseModel.php';

class AppointmentModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    // دالة حساب إجمالي المواعيد المطلوبة للوحة الأدمن (تصحيح خطأ لاين 30)
    public function countAll() {
        try {
            $sql = "SELECT COUNT(*) as total FROM appointments";
            $stmt = $this->db->query($sql);
            if ($stmt && method_exists($stmt, 'get_result')) {
                $result = $stmt->get_result()->fetch_assoc();
                return $result['total'] ?? 0;
            }
        } catch (Exception $e) {
            // حماية في حال عدم وجود الجدول
        }
        return 0;
    }

    // جلب جميع المواعيد
    public function getAll() {
        try {
            $sql = "SELECT * FROM appointments ORDER BY id DESC";
            $stmt = $this->db->query($sql);
            if ($stmt && method_exists($stmt, 'get_result')) {
                $result = $stmt->get_result();
                if ($result) {
                    return $result->fetch_all(MYSQLI_ASSOC);
                }
            }
        } catch (Exception $e) {
            // حماية
        }
        return [];
    }

    // دالة إنشاء موعد
    public function create($patientId, $doctorId, $specId, $date, $time, $notes) {
        try {
            $sql = "INSERT INTO appointments (patient_id, doctor_id, specialization_id, appointment_date, appointment_time, notes, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
            return $this->db->query($sql, "iiisss", [$patientId, $doctorId, $specId, $date, $time, $notes]);
        } catch (Exception $e) {
            return false;
        }
    }

    // دالة تحديث الحالة
    public function updateStatus($appointmentId, $status) {
        try {
            $sql = "UPDATE appointments SET status = ? WHERE id = ?";
            return $this->db->query($sql, "si", [$status, $appointmentId]);
        } catch (Exception $e) {
            return false;
        }
    }
}