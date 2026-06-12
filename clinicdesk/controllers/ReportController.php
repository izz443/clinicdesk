<?php
require_once __DIR__ . '/../models/AppointmentModel.php';

class ReportController {
    private $appointmentModel;

    public function __construct() {
        Auth::requireRole('admin');
        $this->appointmentModel = new AppointmentModel();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        // حساب إجمالي الدخل المالي التراكمي من الكشوفات المكتملة بنجاح
        $incomeQuery = "SELECT SUM(d.consultation_fee) as total_revenue 
                        FROM appointments a 
                        JOIN doctors d ON a.doctor_id = d.id 
                        WHERE a.status = 'completed'";
        $revenue = $db->query($incomeQuery)->fetch_assoc()['total_revenue'];

        // الفرز والإحصاء لعدد المواعيد بناءً على حالتها الحالية
        $statusQuery = "SELECT status, COUNT(*) as count FROM appointments GROUP BY status";
        $statusCounts = $db->query($statusQuery)->fetch_all(MYSQLI_ASSOC);

        include __DIR__ . '/../views/reports/index.php';
    }
}
