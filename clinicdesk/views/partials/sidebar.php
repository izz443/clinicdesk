<?php $role = Auth::role(); ?>
<nav id="sidebar">
    <div class="sidebar-header">
        <h3>ClinicDesk</h3>
    </div>
    <ul class="list-unstyled components">
        <li class="<?php echo ($_GET['page'] == 'dashboard') ? 'active' : ''; ?>">
            <a href="index.php?page=dashboard&action=index"><i class="fa-solid fa-gauge"></i> الرئيسية</a>
        </li>
        
        <?php if ($role === 'admin'): ?>
            <li class="<?php echo ($_GET['page'] == 'users') ? 'active' : ''; ?>">
                <a href="index.php?page=users&action=index"><i class="fa-solid fa-users"></i> إدارة المرضى</a>
            </li>
            <li class="<?php echo ($_GET['page'] == 'doctors') ? 'active' : ''; ?>">
                <a href="index.php?page=doctors&action=index"><i class="fa-solid fa-user-md"></i> إدارة الأطباء</a>
            </li>
            <li class="<?php echo ($_GET['page'] == 'reports') ? 'active' : ''; ?>">
                <a href="index.php?page=reports&action=index"><i class="fa-solid fa-chart-line"></i> التقارير المالية</a>
            </li>
        <?php endif; ?>

        <?php if ($role === 'patient' || $role === 'doctor' || $role === 'admin'): ?>
            <li class="<?php echo ($_GET['page'] == 'appointments') ? 'active' : ''; ?>">
                <a href="index.php?page=appointments&action=index"><i class="fa-solid fa-calendar-check"></i> المواعيد الطبية</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<div id="content">
