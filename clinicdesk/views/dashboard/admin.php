<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/sidebar.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<?php include __DIR__ . '/../partials/alerts.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">لوحة تحكم المسؤول الإداري</h1>
    
    <div class="row">
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info"><i class="fa-solid fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي المرضى</span>
                    <span class="info-box-number"><?php echo $stats['total_patients']; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success"><i class="fa-solid fa-user-md"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي الأطباء</span>
                    <span class="info-box-number"><?php echo $stats['total_doctors']; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning"><i class="fa-solid fa-calendar-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي المواعيد</span>
                    <span class="info-box-number"><?php echo $stats['total_appointments']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-4 text-center">
            <h4 class="text-secondary fw-bold">مرحباً بك في نظام إدارة عيادة ClinicDesk</h4>
            <p class="text-muted">استخدم القائمة الجانبية للتنقل وتعديل بيانات المرضى، الأطباء، أو استخراج التقارير المالية للعيادة.</p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
