<?php
// views/dashboard/admin.php

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6 text-right">
          <h1 class="m-0 text-dark"><?= sanitize($pageTitle); ?></h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        
        <div class="col-lg-3 col-6 text-right">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= (int)($totalDoctors ?? 0); ?></h3>
              <p>إجمالي الأطباء</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-md"></i>
            </div>
            <a href="index.php?page=users" class="small-box-footer">إدارة الأطباء <i class="fas fa-arrow-circle-left"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6 text-right">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= (int)($totalPatients ?? 0); ?></h3>
              <p>إجمالي المرضى</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="index.php?page=users" class="small-box-footer">إدارة المرضى <i class="fas fa-arrow-circle-left"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6 text-right">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= (int)($totalAppointments ?? 0); ?></h3>
              <p>إجمالي المواعيد</p>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-check"></i>
            </div>
            <a href="#" class="small-box-footer">عرض المواعيد <i class="fas fa-arrow-circle-left"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6 text-right">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><?= (int)($totalSpecializations ?? 0); ?></h3>
              <p>التخصصات الطبية</p>
            </div>
            <div class="icon">
              <i class="fas fa-stethoscope"></i>
            </div>
            <a href="index.php?page=specializations" class="small-box-footer">إدارة التخصصات <i class="fas fa-arrow-circle-left"></i></a>
          </div>
        </div>

      </div>

      <div class="row">
        <div class="col-12 text-right">
          <div class="card card-outline card-primary">
            <div class="card-body">
              <h5>أهلاً بك يا <b><?= sanitize(Auth::currentUser()['name'] ?? 'Admin User'); ?></b> في نظام إدارة العيادة الموحد.</h5>
              <p class="text-muted mb-0">يمكنك من خلال القائمة الجانبية الكاملة التحكم بالمستخدمين وتنشيط أو تجميد الحسابات الطبية، بالإضافة لإنشاء التخصصات ومراقبة حركة الحجوزات والتقارير.</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php
require_once __DIR__ . '/../partials/footer.php';
?>