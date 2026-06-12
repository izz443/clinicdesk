<?php
require_once __DIR__ . '/BaseModel.php';

class PrescriptionModel extends BaseModel {

    // جلب وصفة طبية محددة مع تفاصيل الطبيب، المريض، والموعد المرتبط بها
    public function getById($id) {
        $sql = "SELECT pr.*, a.appt_date, p.name as patient_name, p.id as patient_id, 
                       u.name as doctor_name, d.id as doctor_id, s.name as specialization_name
                FROM prescriptions pr
                JOIN appointments a ON pr.appointment_id = a.id
                JOIN users p ON a.patient_id = p.id
                JOIN doctors d ON a.doctor_id = d.id
                JOIN users u ON d.user_id = u.id
                JOIN specializations s ON d.specialization_id = s.id
                WHERE pr.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // جلب الوصفة الطبية بناءً على معرف الموعد المرتبط بها
    public function getByAppointmentId($appointmentId) {
        $sql = "SELECT * FROM prescriptions WHERE appointment_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // إضافة وصفة طبية جديدة للموعد المنتهي
    public function create($data) {
        $sql = "INSERT INTO prescriptions (appointment_id, diagnosis, medications, notes, file_path) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", 
            $data['appointment_id'], 
            $data['diagnosis'], 
            $data['medications'], 
            $data['notes'], 
            $data['file_path']
        );
        return $stmt->execute();
    }
}
