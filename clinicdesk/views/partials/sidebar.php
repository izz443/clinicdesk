<?php
// views/partials/sidebar.php

$currentUser = $_SESSION['user'] ?? null;
$role = $currentUser['role'] ?? '';
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="direction: rtl; text-align: right;">
  <a href="index.php?page=dashboard" class="brand-link text-center">
    <span class="brand-text font-weight-light">نظام ClinicDesk</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-start align-items-center">
      <div class="info w-100 text-center">
        <a href="#" class="d-block">مرحباً، <?= sanitize($currentUser['name'] ?? 'مستخدم'); ?></a>
        <span class="badge badge-info"><?= $role === 'admin' ? 'مسؤول النظام' : ($role === 'doctor' ? 'طبيب المعالج' : 'مريض'); ?></span>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <?php if ($role === 'admin'): ?>
          <li class="nav-item">
            <a href="index.php?page=dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>اللوحة الرئيسية</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php?page=specializations" class="nav-link">
              <i class="nav-icon fas fa-stethoscope"></i>
              <p>إدارة التخصصات</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php?page=users" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>المستخدمين والأطباء</p>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($role === 'doctor'): ?>
          <li class="nav-item">
            <a href="index.php?page=doctor/dashboard" class="nav-link">
              <i class="nav-icon fas fa-calendar-check"></i>
              <p>مواعيد العيادة</p>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($role === 'patient'): ?>
          <li class="nav-item">
            <a href="index.php?page=patient/dashboard" class="nav-link">
              <i class="nav-icon fas fa-calendar-plus"></i>
              <p>حجز موعد جديد</p>
            </a>
          </li>
        <?php endif; ?>

        <hr style="border-color: #4f5962; width: 100%;">

        <li class="nav-item">
          <a href="index.php?page=auth/logout" class="nav-link text-danger">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>تسجيل الخروج</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>