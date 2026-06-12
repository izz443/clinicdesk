<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<?php include __DIR__ . '/../partials/alerts.php'; ?>

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-user-md text-success me-2"></i> إدارة الأطباء والملفات الطبية</h1>
        <a href="index.php?page=doctors&action=create" class="btn btn-success fw-bold shadow-sm">
            <i class="fa-solid fa-plus-circle me-1"></i> تسجيل طبيب جديد
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>الاسم الكلي</th>
                            <th>البريد الإلكتروني</th>
                            <th>التخصص المعتمد</th>
                            <th>سعر الكشفية</th>
                            <th>أيام الدوام</th>
                            <th>الحالة الحسابية</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($doctors)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 fw-bold text-muted">لا يوجد أطباء مسجلين حالياً.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($doctors as $d): ?>
                                <tr>
                                    <td class="fw-bold text-primary">د. <?php echo $d['name']; ?></td>
                                    <td><?php echo $d['email']; ?></td>
                                    <td><span class="badge bg-info text-dark fw-bold"><?php echo $d['specialization_name']; ?></span></td>
                                    <td class="fw-bold text-success">$<?php echo number_format($d['consultation_fee'], 2); ?></td>
                                    <td><small class="fw-bold text-secondary"><?php echo $d['available_days']; ?></small></td>
                                    <td>
                                        <?php if ((int)$d['is_active'] === 1): ?>
                                            <span class="badge bg-success px-2 py-1">نشط</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger px-2 py-1">معطل</span>
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
