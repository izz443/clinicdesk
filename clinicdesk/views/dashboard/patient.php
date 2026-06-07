<?php
// views/dashboard/patient.php

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

      <div class="row" style="direction: rtl;">
        <div class="col-md-4 text-right">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title float-right">طلب حجز موعد جديد</h3>
            </div>
            <form action="index.php?page=patient/dashboard&action=book" method="POST">
              <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
              <div class="card-body">
                
                <div class="form-group">
                  <label>اختر التخصص الطبي <span class="text-danger">*</span></label>
                  <select name="specialization_id" class="form-control" required>
                    <option value="">-- اختر التخصص --</option>
                    <?php foreach($specializations as $spec): ?>
                      <option value="<?= $spec['id']; ?>"><?= sanitize($spec['name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="form-group">
                  <label>اختر الطبيب المعالج <span class="text-danger">*</span></label>
                  <select name="doctor_id" class="form-control" required>
                    <option value="">-- اختر الطبيب --</option>
                    <?php foreach($doctors as $doc): if($doc['is_active'] == 1): ?>
                      <option value="<?= $doc['id']; ?>"><?= sanitize($doc['name']); ?></option>
                    <?php endif; endforeach; ?>
                  </select>
                </div>

                <div class="form-group">
                  <label>تاريخ الموعد <span class="text-danger">*</span></label>
                  <input type="date" name="appointment_date" class="form-control" required min="<?= date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                  <label>الوقت المفضّل <span class="text-danger">*</span></label>
                  <input type="time" name="appointment_time" class="form-control" required>
                </div>

                <div class="form-group">
                  <label>ملاحظات إضافية للطبيب</label>
                  <textarea name="notes" class="form-control" rows="2" placeholder="اكتب شكواك الصحية باختصار..."></textarea>
                </div>

              </div>
              <div class="card-footer text-left">
                <button type="submit" class="btn btn-primary"><i class="fas fa-calendar-plus"></i> تأكيد طلب الحجز</button>
              </div>
            </form>
          </div>
        </div>

        <div class="col-md-8 text-right">
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title float-right">أرشيف وحالة حوزاتك الطبية</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered text-center">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>الطبيب</th>
                    <th>التخصص</th>
                    <th>التاريخ والوقت</th>
                    <th>حالة الطلب</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(empty($myAppointments)): ?>
                    <tr><td colspan="5" class="text-muted">لم تقم بحجز أي مواعيد في النظام حتى الآن.</td></tr>
                  <?php else: ?>
                    <?php foreach($myAppointments as $idx => $app): ?>
                      <tr>
                        <td><?= $idx + 1; ?></td>
                        <td><b><?= sanitize($app['doctor_name']); ?></b></td>
                        <td><span class="badge badge-secondary"><?= sanitize($app['specialization_name']); ?></span></td>
                        <td><?= sanitize($app['appointment_date']) . ' ' . sanitize($app['appointment_time']); ?></td>
                        <td>
                          <?php if($app['status'] === 'pending'): ?>
                            <span class="badge badge-warning">قيد الانتظار</span>
                          <?php elseif($app['status'] === 'approved'): ?>
                            <span class="badge badge-success">مقبول مسبقاً</span>
                          <?php else: ?>
                            <span class="badge badge-danger">تم الإلغاء</span>
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
require_once __DIR__ . '/../views/partials/footer.php';
?>