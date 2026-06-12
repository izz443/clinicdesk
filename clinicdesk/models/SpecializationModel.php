<?php
require_once __DIR__ . '/BaseModel.php';

class SpecializationModel extends BaseModel {
    
    // جلب جميع التخصصات المتوفرة مرتبة أبجدياً
    public function getAll() {
        $sql = "SELECT * FROM specializations ORDER BY name ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // جلب تخصص بواسطة المعرف ID
    public function getById($id) {
        $sql = "SELECT * FROM specializations WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // إضافة تخصص طبي جديد لنظام العيادة
    public function create($name) {
        $sql = "INSERT INTO specializations (name) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    // تحديث مسمى تخصص موجود
    public function update($id, $name) {
        $sql = "UPDATE specializations SET name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }

    // حذف تخصص طبي (بشرط ألا يكون مرتبطاً بأطباء بناءً على قيد RESTRICT)
    public function delete($id) {
        $sql = "DELETE FROM specializations WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
