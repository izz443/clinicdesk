<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container-fluid">
    <div class="card shadow-sm border-0 max-width-600 mx-auto">
        <div class="card-header bg-dark text-white p-3 fw-bold fs-5">
            <i class="fa-solid fa-calendar-plus me-1"></i> حجز طلب موعد طبي جديد لدى العيادة
        </div>
        <div class="card-body p-4">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger font-weight-bold">
                    <ul class="mb-0"> <?php foreach ($errors as $e): ?> <li><?php echo $e; ?></li> <?php endforeach; ?> </ul>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label fw-bold text-primary">1. اختر التخصص الطبي المطلوب أولاً</label>
                <select id="specSelect" class="form-select border-primary" onchange="location = 'index.php?page=appointments&action=create&specialization_id=' + this.value;">
                    <option value="">-- اختر التخصص الطبي المطلوب للفرز --</option>
                    <?php foreach ($specializations as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo ($selected_specialization == $s['id']) ? 'selected' : ''; ?>><?php echo $s['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <form action="index.php?page=appointments&action=create&specialization_id=<?php echo $selected_specialization; ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">2. اختر الطبيب المتوفر</label>
                    <select name="doctor_id" class="form-select" required <?php echo empty($doctors) ? 'disabled' : ''; ?>>
                        <?php if (empty($doctors)): ?>
                            <option value="">-- يرجى اختيار التخصص أولاً لعرض الأطباء المتاحين --</option>
                        <?php else: ?>
                            <option value="">-- اختر الطبيب المعالج --</option>
                            <?php foreach ($doctors as $d): ?>
                                <option value="<?php echo $d['id']; ?>">د. <?php echo $d['name']; ?> (كشفية: $<?php echo $d['consultation_fee']; ?>)</option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">3. اختر تاريخ الكشف</label>
                        <input type="date" name="appt_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">4. اختر توقيت الساعة</label>
                        <select name="appt_time" class="form-select text-start" required>
                            <option value="">-- اختر الساعة --</option>
                            <option value="09:00:00">09:00 AM</option> <option value="09:30:00">09:30 AM</option>
                            <option value="10:00:00">10:00 AM</option> <option value="10:30:00">10:30 AM</option>
                            <option value="11:00:00">11:00 AM</option> <option value="11:30:00">11:30 AM</option>
                            <option value="12:00:00">12:00 PM</option> <option value="12:30:00">12:30 PM</option>
                            <option value="13:00:00">01:00 PM</option> <option value="13:30:00">01:30 PM</option>
                            <option value="14:00:00">02:00 PM</option> <option value="14:30:00">02:30 PM</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">سبب الزيارة أو الأعراض الحالية</label>
                    <input type="text" name="reason" class="form-control" placeholder="مثال: استشارة طبية، آلام في البطن، إلخ.">
                </div>

                <div class="d-flex justify-content-end">
                    <a href="index.php?page=appointments&action=index" class="btn btn-secondary fw-bold me-2">إلغاء</a>
                    <button type="submit" class="btn btn-primary fw-bold px-4" <?php echo empty($doctors) ? 'disabled' : ''; ?>>تأكيد وحجز الموعد الطبي</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>