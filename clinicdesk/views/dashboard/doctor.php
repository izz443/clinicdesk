<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<?php include __DIR__ . '/../partials/alerts.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">لوحة تحكم الطبيب المعالج</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary"><i class="fa-solid fa-notes-medical"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">مواعيدك الطبية</span>
                    <span class="info-box-number"><?php echo $stats['total_appointments']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-4">
            <h5 class="fw-bold text-dark mb-3">أهلاً دكتور: <?php echo Auth::currentUser()['name']; ?></h5>
            <p class="text-muted">يمكنك فحص ومتابعة مواعيد الكشوفات والعمليات القادمة، وتأكيد الحجوزات أو إلغائها، وإصدار الروشتات للمرضى عبر الانتقال لتبويب المواعيد الطبية.</p>
            <a href="index.php?page=appointments&action=index" class="btn btn-primary fw-bold">
                <i class="fa-solid fa-calendar-days me-1"></i> استعراض جدول المواعيد
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
