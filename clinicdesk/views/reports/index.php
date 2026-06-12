<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><i class="fa-solid fa-chart-pie text-dark me-2"></i> التقارير الإدارية والمالية الشاملة للعيادة</h1>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2 bg-success text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 fs-5 fw-bold text-white-50">إجمالي الدخل المالي للعيادة (Revenue)</div>
                            <div class="h5 mb-0 font-weight-bold fs-2 fw-bold">$<?php echo number_format((float)$revenue, 2); ?></div>
                        </div>
                        <div class="col-auto fs-1 opacity-50">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 max-width-600">
        <div class="card-header bg-dark text-white fw-bold">
            <i class="fa-solid fa-list-check me-1"></i> تحليل إحصائيات المواعيد الكلية بالحالات
        </div>
        <div class="card-body p-0">
            <table class="table table-striped align-middle text-center mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th>حالة طلب الحجز الطبي</th>
                        <th>عدد المواعيد الكلية المدرجة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($statusCounts)): ?>
                        <tr> <td colspan="2" class="text-center fw-bold text-muted py-3">لا توجد مواعيد مسجلة بالنظام حتى الآن.</td></tr>
                    <?php else: ?>
                        <?php foreach ($statusCounts as $s): ?>
                            <tr>
                                <td class="fw-bold">
                                    <?php 
                                    if ($s['status'] === 'pending') echo 'قيد الانتظار';
                                    elseif ($s['status'] === 'confirmed') echo 'مؤكد ومثبت';
                                    elseif ($s['status'] === 'completed') echo 'مكتمل ومنتهي';
                                    elseif ($s['status'] === 'cancelled') echo 'ملغي';
                                    ?>
                                </td>
                                <td><span class="badge bg-dark fs-6 px-3 py-1"><?php echo $s['count']; ?> موعد</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
