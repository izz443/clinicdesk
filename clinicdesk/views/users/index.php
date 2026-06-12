<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<?php include __DIR__ . '/../partials/alerts.php'; ?>

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-users text-primary me-2"></i> إدارة المرضى</h1>
        <a href="index.php?page=users&action=create" class="btn btn-primary fw-bold shadow-sm">
            <i class="fa-solid fa-user-plus me-1"></i> إضافة حساب مريض
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>الصورة الرمزية</th>
                            <th>الاسم كاملاً</th>
                            <th>البريد الإلكتروني</th>
                            <th>رقم الهاتف</th>
                            <th>الحالة</th>
                            <th>الإجراءات والتحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 fw-bold text-muted">لا يوجد مرضى مسجلين في النظام حالياً.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($u['avatar'])): ?>
                                            <img src="public/<?php echo $u['avatar']; ?>" class="rounded-circle border shadow-sm" width="45" height="45" alt="Avatar">
                                        <?php else: ?>
                                            <i class="fa-solid fa-user-circle text-secondary fs-2"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold"><?php echo $u['name']; ?></td>
                                    <td><?php echo $u['email']; ?></td>
                                    <td><?php echo !empty($u['phone']) ? $u['phone'] : 'غير مسجل'; ?></td>
                                    <td>
                                        <?php if ((int)$u['is_active'] === 1): ?>
                                            <span class="badge bg-success px-2 py-1 fs-7">نشط</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger px-2 py-1 fs-7">معطل</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?page=users&action=toggle&id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-warning fw-bold me-1">
                                            <i class="fa-solid fa-power-off"></i> تبديل الحالة
                                        </a>
                                        <a href="index.php?page=users&action=delete&id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger fw-bold" onclick="return confirm('هل أنت متأكد من حذف حساب المريض نهائياً؟');">
                                            <i class="fa-solid fa-trash"></i> حذف
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if ($paginator->totalPages() > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center shadow-sm">
                <li class="page-item <?php echo !$paginator->hasPrev() ? 'disabled' : ''; ?>">
                    <a class="page-link font-weight-bold" href="index.php?page=users&action=index&p=<?php echo $paginator->getCurrentPage() - 1; ?>">السابق</a>
                </li>
                <?php for ($i = 1; $i <= $paginator->totalPages(); $i++): ?>
                    <li class="page-item <?php echo ($paginator->getCurrentPage() == $i) ? 'active' : ''; ?>">
                        <a class="page-link font-weight-bold" href="index.php?page=users&action=index&p=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo !$paginator->hasNext() ? 'disabled' : ''; ?>">
                    <a class="page-link font-weight-bold" href="index.php?page=users&action=index&p=<?php echo $paginator->getCurrentPage() + 1; ?>">التالي</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
