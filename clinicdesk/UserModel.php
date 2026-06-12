<?php
require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel {
    
    // جلب مستخدم بواسطة بريده الإلكتروني (يستخدم في تسجيل الدخول)
    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // جلب مستخدم بواسطة معرفه الرقمي ID
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // حساب إجمالي عدد المستخدمين بناءً على دورهم (للتقسيم والإحصائيات)
    public function countAll($role = null) {
        if ($role) {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $role);
        } else {
            $sql = "SELECT COUNT(*) as total FROM users";
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return (int)$result['total'];
    }

    // جلب مستخدمين مع التقسيم والصفحات لجدول الإدارة
    public function getAllPaginated($limit, $offset, $role = null) {
        if ($role) {
            $sql = "SELECT * FROM users WHERE role = ? ORDER BY id DESC LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sii", $role, $limit, $offset);
        } else {
            $sql = "SELECT * FROM users ORDER BY id DESC LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $limit, $offset);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // إنشاء حساب مستخدم جديد في النظام
    public function create($data) {
        $sql = "INSERT INTO users (name, email, password, role, phone, avatar, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssi", 
            $data['name'], 
            $data['email'], 
            $data['password'], 
            $data['role'], 
            $data['phone'], 
            $data['avatar'], 
            $data['is_active']
        );
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    // تحديث بيانات حساب مستخدم موجود
    public function update($id, $data) {
        $sql = "UPDATE users SET name = ?, email = ?, role = ?, phone = ?, avatar = ?, is_active = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssii", 
            $data['name'], 
            $data['email'], 
            $data['role'], 
            $data['phone'], 
            $data['avatar'], 
            $data['is_active'], 
            $id
        );
        return $stmt->execute();
    }

    // تحديث كلمة مرور مستخدم بشكل منفصل
    public function updatePassword($id, $hashedPassword) {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $id);
        return $stmt->execute();
    }

    // تفعيل أو تعطيل الحساب من قبل المسؤول
    public function toggleStatus($id, $status) {
        $sql = "UPDATE users SET is_active = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $status, $id);
        return $stmt->execute();
    }

    // حذف مستخدم من النظام نهائياً
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}