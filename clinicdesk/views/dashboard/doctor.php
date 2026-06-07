<?php
// views/dashboard/doctor.php

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

      <?php if ($success = flash('success')): ?>
        <div class="alert alert-success text-right"><?= sanitize($success); ?></div>
      <?php endif; ?>
      <?php if ($error = flash('error')): ?>
        <div class="alert alert-danger text-right"><?= sanitize($error); ?></div>
      <?php endif; ?>

      <div class="row">
        <div class="col-12 text-right">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title float-right">جدول المواعيد المحجوزة لدى عيادتك</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped text-center">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>اسم المريض</th>
                    <th>التاريخ</th>
                    <th>الوقت</th>
                    <th>ملاحظات</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(empty($appointments)): ?>
                    <tr><td colspan="7" class="text-muted">لا يوجد مواعيد مجدولة لديك حالياً.</td></tr>
                  <?php else: ?>
                    <?php foreach($appointments as $index => $app): ?>
                      <tr>
                        <td><?= $index + 1; ?></td>
                        <td><b><?= sanitize($app['patient_name']); ?></b></td>
                        <td><?= sanitize($app['appointment_date']); ?></td>
                        <td><?= sanitize($app['appointment_time']); ?></td>
                        <td><?= sanitize($app['notes'] ?: '---'); ?></td>
                        <td>
                          <?php if($app['status'] === 'pending'): ?>
                            <span class="badge badge-warning">قيد الانتظار</span>
                          <?php elseif($app['status'] === 'approved'): ?>
                            <span class="badge badge-success">مقبول</span>
                          <?php else: ?>
                            <span class="badge badge-danger">ملغي</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if($app['status'] === 'pending'): ?>
                            <form action="index.php?page=doctor/dashboard&action=update-status" method="POST" style="display:inline;">
                              <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
                              <input type="hidden" name="appointment_id" value="<?= (int)$app['id']; ?>">
                              <input type="hidden" name="status" value="approved">
                              <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> قبول</button>
                            </form>
                            <form action="index.php?page=doctor/dashboard&action=update-status" method="POST" style="display:inline;">
                              <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
                              <input type="hidden" name="appointment_id" value="<?= (int)$app['id']; ?>">
                              <input type="hidden" name="status" value="cancelled">
                              <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> إلغاء</button>
                            </form>
                          <?php else: ?>
                            <span class="text-muted">مغلق</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
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