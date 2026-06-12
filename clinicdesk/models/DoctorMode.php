<?php
require_once __DIR__ . '/BaseModel.php';

class DoctorModel extends BaseModel {

    // جلب الملف التعريفي للطبيب مع بيانات المستخدم والتخصص
    public function getById($id) {
        $sql = "SELECT d.*, u.name, u.email, u.phone, u.avatar, u.is_active, s.name as specialization_name 
                FROM doctors d
                JOIN users u ON d.user_id = u.id
                JOIN specializations s ON d.specialization_id = s.id
                WHERE d.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // جلب بيانات الطبيب بناءً على رقم حسابه كمستخدم (User ID)
    public function getByUserId($userId) {
        $sql = "SELECT d.*, u.name, u.email, u.phone, u.avatar FROM doctors d
                JOIN users u ON d.user_id = u.id 
                WHERE d.user_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // حساب إجمالي عدد الأطباء المسجلين
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM doctors";
        $result = $this->conn->query($sql);
        return (int)$result->fetch_assoc()['total'];
    }

    // جلب الأطباء مع الفرز والصفحات للوحة التحكم الإدارية
    public function getAllPaginated($limit, $offset) {
        $sql = "SELECT d.*, u.name, u.email, u.is_active, s.name as specialization_name 
                FROM doctors d
                JOIN users u ON d.user_id = u.id
                JOIN specializations s ON d.specialization_id = s.id
                ORDER BY d.id DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // جلب جميع الأطباء المتاحين في تخصص معين لحساب المريض
    public function getBySpecialization($specializationId) {
        $sql = "SELECT d.*, u.name FROM doctors d
                JOIN users u ON d.user_id = u.id
                WHERE d.specialization_id = ? AND u.is_active = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $specializationId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ربط مستخدم بملف طبيب جديد
    public function create($data) {
        $sql = "INSERT INTO doctors (user_id, specialization_id, bio, consultation_fee, available_days) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisds", 
            $data['user_id'], 
            $data['specialization_id'], 
            $data['bio'], 
            $data['consultation_fee'], 
            $data['available_days']
        );
        return $stmt->execute();
    }

    // تحديث البيانات الطبية الخاصة بالطبيب
    public function update($id, $data) {
        $sql = "UPDATE doctors SET specialization_id = ?, bio = ?, consultation_fee = ?, available_days = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isdsi", 
            $data['specialization_id'], 
            $data['bio'], 
            $data['consultation_fee'], 
            $data['available_days'], 
            $id
        );
        return $stmt->execute();
    }
}
