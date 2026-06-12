<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<?php include __DIR__ . '/../partials/alerts.php'; ?>

<?php $userRole = Auth::role(); ?>
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-calendar-check text-primary me-2"></i> سجل وجدول المواعيد الطبية</h1>
        <?php if ($userRole === 'patient'): ?>
            <a href="index.php?page=appointments&action=create" class="btn btn-primary fw-bold shadow-sm">
                <i class="fa-solid fa-plus-circle me-1"></i> حجز موعد كشف جديد
            </a>
        <?php endif; ?>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>المريض</th>
                            <th>الطبيب المختص</th>
                            <th>التخصص</th>
                            <th>تاريخ الكشف</th>
                            <th>الساعة والوقت</th>
                            <th>حالة الحجز</th>
                            <th>الإجراء والعملية</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 fw-bold text-muted">لا يوجد حجوزات مواعيد مدرجة في النظام لك حالياً.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($appointments as $a): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $a['patient_name']; ?></td>
                                    <td class="text-primary fw-bold">د. <?php echo $a['doctor_name']; ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $a['specialization_name']; ?></span></td>
                                    <td><span class="fw-bold text-secondary"><?php echo formatDate($a['appt_date']); ?></span></td>
                                    <td><span class="badge bg-dark"><?php echo formatTime($a['appt_time']); ?></span></td>
                                    <td>
                                        <?php 
                                        if ($a['status'] === 'pending') echo '<span class="badge bg-warning text-dark px-2 py-1">قيد الانتظار</span>';
                                        elseif ($a['status'] === 'confirmed') echo '<span class="badge bg-primary px-2 py-1">مؤكد ومثبت</span>';
                                        elseif ($a['status'] === 'completed') echo '<span class="badge bg-success px-2 py-1">مكتمل ومنتهي</span>';
                                        elseif ($a['status'] === 'cancelled') echo '<span class="badge bg-danger px-2 py-1">ملغي</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($a['status'] === 'pending' && ($userRole === 'admin' || $userRole === 'doctor')): ?>
                                            <a href="index.php?page=appointments&action=updateStatus&id=<?php echo $a['id']; ?>&status=confirmed" class="btn btn-sm btn-success fw-bold me-1">تأكيد</a>
                                            <a href="index.php?page=appointments&action=updateStatus&id=<?php echo $a['id']; ?>&status=cancelled" class="btn btn-sm btn-outline-danger fw-bold" onclick="return confirm('هل تريد إلغاء حجز هذا الموعد الطبي؟');">إلغاء</a>
                                        <?php endif; ?>

                                        <?php if ($a['status'] === 'confirmed' && $userRole === 'doctor'): ?>
                                            <a href="index.php?page=prescriptions&action=create&appointment_id=<?php echo $a['id']; ?>" class="btn btn-sm btn-primary fw-bold">
                                                <i class="fa-solid fa-file-medical"></i> إنهاء الكشف وإصدار روشتة
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($a['status'] === 'completed'): 
                                            // التحقق من وجود الروشتة لعرض رابط التنزيل الآمن
                                            $dbCheck = Database::getInstance()->getConnection();
                                            $pCheck = $dbCheck->query("SELECT id FROM prescriptions WHERE appointment_id = " . (int)$a['id'])->fetch_assoc();
                                            if ($pCheck && $userRole !== 'admin'): ?>
                                                <a href="index.php?page=prescriptions&action=download&id=<?php echo $pCheck['id']; ?>" target="_blank" class="btn btn-sm btn-outline-success fw-bold">
                                                    <i class="fa-solid fa-download"></i> تنزيل الروشتة الـ PDF
                                                </a>
                                            <?php elseif ($userRole === 'admin'): ?>
                                                <span class="text-muted small italic"><i class="fa-solid fa-lock"></i> محجوبة للخصوصية</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>