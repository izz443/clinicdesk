<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container-fluid">
    <div class="card shadow-sm border-0 max-width-700 mx-auto">
        <div class="card-header bg-dark text-white p-3 fw-bold fs-5">
            <i class="fa-solid fa-user-plus me-1"></i> إضافة حساب مريض جديد
        </div>
        <div class="card-body p-4">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger font-weight-bold">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?> <li><?php echo $e; ?></li> <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="index.php?page=users&action=create" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">الاسم كاملاً</label>
                    <input type="text" name="name" class="form-control" required placeholder="أدخل اسم المريض الرباعي">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control text-start" required placeholder="name@domain.com">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">كلمة المرور الحسابية</label>
                    <input type="password" name="password" class="form-control text-start" required placeholder="أدخل كلمة مرور قوية">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">رقم الهاتف الجوال</label>
                    <input type="text" name="phone" class="form-control text-start" placeholder="059xxxxxxx">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">الصورة الشخصية (Avatar)</label>
                    <input type="file" name="avatar" class="form-control" accept="image/png, image/jpeg">
                    <small class="text-muted">الصيغ المسموحة: JPG, PNG وبحد أقصى 1 ميغابايت.</small>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActiveSwitch" checked>
                    <label class="form-check-label fw-bold" for="isActiveSwitch">تفعيل الحساب فورا فور الحفظ</label>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="index.php?page=users&action=index" class="btn btn-secondary fw-bold me-2">إلغاء وتراجع</a>
                    <button type="submit" class="btn btn-primary fw-bold px-4">حفظ وإضافة المريض</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
