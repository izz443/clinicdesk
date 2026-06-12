<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container-fluid">
    <div class="card shadow-sm border-0 max-width-700 mx-auto">
        <div class="card-header bg-primary text-white p-3 fw-bold fs-5">
            <i class="fa-solid fa-file-medical me-1"></i> إصدار الروشتة والتقرير الطبي للموعد #<?php echo $appointment['id']; ?>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info py-2 shadow-sm border">
                <p class="mb-1"><strong>المريض المعالج:</strong> <?php echo $appointment['patient_name']; ?></p>
                <p class="mb-0"><strong>سبب الحجز الموضح:</strong> <?php echo !empty($appointment['reason']) ? $appointment['reason'] : 'لا يوجد'; ?></p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger font-weight-bold">
                    <ul class="mb-0"> <?php foreach ($errors as $e): ?> <li><?php echo $e; ?></li> <?php endforeach; ?> </ul>
                </div>
            <?php endif; ?>

            <form action="index.php?page=prescriptions&action=create&appointment_id=<?php echo $appointment['id']; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">التشخيص الطبي للحالة (Diagnosis)</label>
                    <textarea name="diagnosis" class="form-control" rows="3" required placeholder="اكتب التشخيص التفصيلي للحالة والشكوى..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-success">الأدوية الموصوفة والجرعات المعتمدة (Medications)</label>
                    <textarea name="medications" class="form-control text-start font-monospace" rows="4" required placeholder="1. Medication Name - 500mg (1x3 Daily)&#10;2. Syrup - 10ml (2x2 Daily)"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">ملاحظات طبية وإرشادات إضافية للمريض</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="أي ملاحظات عامة كأوقات المراجعة أو الحمية الغذائية المطلوبة..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">إرفاق مستند الروشتة المطبوع بصيغة (PDF المباشر)</label>
                    <input type="file" name="prescription_file" class="form-control" accept="application/pdf" required>
                    <small class="text-muted">الرفع الإجباري للمستند بصيغة PDF فقط وحجم لا يتخطى الـ 2 ميجابايت.</small>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="index.php?page=appointments&action=index" class="btn btn-secondary fw-bold me-2">رجوع للحجوزات</a>
                    <button type="submit" class="btn btn-success fw-bold px-4">إكمال الكشف وحفظ الروشتة نهائياً</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
