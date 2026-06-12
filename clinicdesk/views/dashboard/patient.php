<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<?php include __DIR__ . '/../partials/alerts.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">الملف الطبي للمريض</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-secondary"><i class="fa-solid fa-clock-rotate-left"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">حجوزاتك السابقة والحالية</span>
                    <span class="info-box-number"><?php echo $stats['total_appointments']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-4">
            <h5 class="fw-bold text-success mb-3">أهلاً بك: <?php echo Auth::currentUser()['name']; ?></h5>
            <p class="text-muted">مرحباً بك في نظام عيادتنا. يمكنك حجز موعد كشف جديد لدى أي طبيب متخصص داخل العيادة، ومتابعة حالة طلبك وتنزيل الروشتات الطبية المعتمدة فور صدورها.</p>
            <a href="index.php?page=appointments&action=create" class="btn btn-success fw-bold">
                <i class="fa-solid fa-plus-circle me-1"></i> حجز كشف طبي جديد الآن
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
