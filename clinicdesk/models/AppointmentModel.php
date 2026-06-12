<?php

class AppointmentModel {
    private $db;

    public function __construct() {
        // جلب اتصال قاعدة البيانات المركزي
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * جلب المواعيد بنظام الصفحات (Pagination) وحسب رتبة المستخدم مع التخصص الطبي
     */
    public function getAllPaginated($role, $userId, $limit, $offset) {
        $dbConnection = Database::getInstance()->getConnection();

        // بناء استعلام متوافق مع ملف العرض وجلب اسم التخصص بدقة
        $query = "SELECT a.*, 
                         p.name AS patient_name, 
                         d_user.name AS doctor_name,
                         s.name AS specialization_name
                  FROM appointments a
                  JOIN users p ON a.patient_id = p.id
                  JOIN doctors d ON a.doctor_id = d.id
                  JOIN users d_user ON d.user_id = d_user.id
                  JOIN specializations s ON d.specialization_id = s.id";

        // تصفية النتائج بناءً على رتبة المستخدم الحالي
        if ($role === 'doctor') {
            $query .= " WHERE d.user_id = ?";
        } elseif ($role === 'patient') {
            $query .= " WHERE a.patient_id = ?";
        }

        $query .= " ORDER BY a.appt_date DESC, a.appt_time DESC LIMIT ? OFFSET ?";

        $stmt = $dbConnection->prepare($query);

        // الربط الصحيح والديناميكي حسب الرتبة لمنع تفاوت المتغيرات
        if ($role === 'doctor' || $role === 'patient') {
            $stmt->bind_param("iii", $userId, $limit, $offset);
        } else {
            $stmt->bind_param("ii", $limit, $offset);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * جلب العدد الإجمالي للمواعيد لحساب عدد الصفحات
     */
    public function countAll($role, $userId) {
        $dbConnection = Database::getInstance()->getConnection();
        
        $query = "SELECT COUNT(*) as total FROM appointments a
                  JOIN doctors d ON a.doctor_id = d.id";

        if ($role === 'doctor') {
            $query .= " WHERE d.user_id = ?";
        } elseif ($role === 'patient') {
            $query .= " WHERE a.patient_id = ?";
        }

        $stmt = $dbConnection->prepare($query);

        if ($role === 'doctor' || $role === 'patient') {
            $stmt->bind_param("i", $userId);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    /**
     * جلب تفاصيل موعد محدد
     */
    public function getById($id) {
        $dbConnection = Database::getInstance()->getConnection();
        
        $query = "SELECT a.*, p.name as patient_name, d_user.name as doctor_name 
                  FROM appointments a
                  JOIN users p ON a.patient_id = p.id
                  JOIN doctors d ON a.doctor_id = d.id
                  JOIN users d_user ON d.user_id = d_user.id
                  WHERE a.id = ? LIMIT 1";

        $stmt = $dbConnection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * حجز موعد جديد
     */
    public function create($patientId, $doctorId, $date, $time, $reason) {
        $dbConnection = Database::getInstance()->getConnection();
        
        $query = "INSERT INTO appointments (patient_id, doctor_id, appt_date, appt_time, reason, status) 
                  VALUES (?, ?, ?, ?, ?, 'pending')";
                  
        $stmt = $dbConnection->prepare($query);
        $stmt->bind_param("iisss", $patientId, $doctorId, $date, $time, $reason);
        return $stmt->execute();
    }

    /**
     * تحديث حالة الموعد (تأكيد، إلغاء، إكمال)
     */
    public function updateStatus($id, $status) {
        $dbConnection = Database::getInstance()->getConnection();
        
        $query = "UPDATE appointments SET status = ? WHERE id = ?";
        $stmt = $dbConnection->prepare($query);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
}
