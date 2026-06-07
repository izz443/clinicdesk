<?php
// views/specializations/index.php

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
        <div class="col-md-4 text-right">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title float-right">إضافة تخصص جديد</h3>
            </div>
            <form action="index.php?page=specializations&action=create" method="POST">
              <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
              <div class="card-body">
                <div class="form-group">
                  <label for="name">اسم التخصص الطبي</label>
                  <input type="text" name="name" class="form-control" id="name" placeholder="مثال: طب الأطفال" required>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-block">حفظ التخصص</button>
              </div>
            </form>
          </div>
        </div>

        <div class="col-md-8 text-right">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title float-right">جدول التخصصات المتاحة</h3>
            </div>
            <div class="card-body p-0">
              <table class="table table-striped table-bordered text-center">
                <thead>
                  <tr>
                    <th style="width: 10%">#</th>
                    <th>اسم التخصص</th>
                    <th style="width: 20%">الإجراءات</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($specializations)): ?>
                    <tr>
                      <td colspan="3" class="text-muted">لا يوجد تخصصات مضافة حالياً.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($specializations as $index => $spec): ?>
                      <tr>
                        <td><?= $index + 1; ?></td>
                        <td><b><?= sanitize($spec['name']); ?></b></td>
                        <td>
                          <form action="index.php?page=specializations&action=delete" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التخصص نهائياً؟');" style="display:inline-block;">
                            <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
                            <input type="hidden" name="id" value="<?= (int)$spec['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                              <i class="fas fa-trash"></i> حذف
                            </button>
                          </form>
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