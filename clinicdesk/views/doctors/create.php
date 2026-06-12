<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container-fluid">
    <div class="card shadow-sm border-0 max-width-800 mx-auto">
        <div class="card-header bg-dark text-white p-3 fw-bold fs-5">
            <i class="fa-solid fa-stethoscope me-1"></i> تسجيل طبيب جديد وتثبيت ملفه المهني
        </div>
        <div class="card-body p-4">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger font-weight-bold">
                    <ul class="mb-0"> <?php foreach ($errors as $e): ?> <li><?php echo $e; ?></li> <?php endforeach; ?> </ul>
                </div>
            <?php endif; ?>

            <form action="index.php?page=doctors&action=create" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">اسم الطبيب كاملاً</label>
                        <input type="text" name="name" class="form-control" required placeholder="مثال: د. محمد العلي">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">البريد الإلكتروني المهني</label>
                        <input type="email" name="email" class="form-control text-start" required placeholder="doctor@clinic.local">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">كلمة المرور لحساب الطبيب</label>
                        <input type="password" name="password" class="form-control text-start" required placeholder="••••••••">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">رقم الجوال الشخصي</label>
                        <input type="text" name="phone" class="form-control text-start" placeholder="059xxxxxxx">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">التخصص الطبي المعتمد</label>
                        <select name="specialization_id" class="form-select" required>
                            <option value="">-- اختر التخصص الطبي --</option>
                            <?php foreach ($specializations as $s): ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo $s['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">رسوم الكشفية والاستشارة ($)</label>
                        <input type="number" step="0.01" name="consultation_fee" class="form-control text-start" required placeholder="0.00">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">النبذة المهنية والخبرات الطبية (Bio)</label>
                    <textarea name="bio" class="form-control" rows="3" placeholder="اكتب نبذة مختصرة عن الطبيب وخبراته والجامعات المتخرج منها..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold d-block">أيام العمل المتاحة للكشوفات</label>
                    <div class="btn-group w-100" role="group">
                        <?php 
                        $weekdays = ['Sun' => 'الأحد', 'Mon' => 'الاثنين', 'Tue' => 'الثلاثاء', 'Wed' => 'الأربعاء', 'Thu' => 'الخميس', 'Fri' => 'الجمعة', 'Sat' => 'السبت'];
                        foreach ($weekdays as $key => $name): ?>
                            <input type="checkbox" class="btn-check" name="days[]" value="<?php echo $key; ?>" id="day_<?php echo $key; ?>" <?php echo in_array($key, ['Sun','Mon','Tue','Wed','Thu']) ? 'checked' : ''; ?>>
                            <label class="btn btn-outline-secondary fw-bold" for="day__<?php echo $key; ?>"><?php echo $name; ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">صورة الطبيب الشخصية (الاختيارية)</label>
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                </div>

                <div class="d-flex justify-content-end">
                    <a href="index.php?page=doctors&action=index" class="btn btn-secondary fw-bold me-2">تراجع</a>
                    <button type="submit" class="btn btn-success fw-bold px-4">تسجيل الطبيب بالكامل</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
