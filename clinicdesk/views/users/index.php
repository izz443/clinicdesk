<?php
// views/users/index.php

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
          
          <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="doctors-tab" data-toggle="pill" href="#doctors-content" role="tab"><b>قائمة الأطباء</b></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="patients-tab" data-toggle="pill" href="#patients-content" role="tab"><b>قائمة المرضى</b></a>
                </li>
              </ul>
            </div>
            
            <div class="card-body">
              <div class="tab-content">
                
                <div class="tab-pane fade show active" id="doctors-content" role="tabpanel">
                  <table class="table table-bordered table-striped text-center">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>حالة الحساب</th>
                        <th>الإجراء</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(empty($doctors)): ?>
                        <tr><td colspan="5" class="text-muted">لا يوجد أطباء مضافين حالياً.</td></tr>
                      <?php else: ?>
                        <?php foreach($doctors as $index => $doc): ?>
                          <tr>
                            <td><?= $index + 1; ?></td>
                            <td><b><?= sanitize($doc['name']); ?></b></td>
                            <td><?= sanitize($doc['email']); ?></td>
                            <td>
                              <?= $doc['is_active'] == 1 
                                ? '<span class="badge badge-success">نشط</span>' 
                                : '<span class="badge badge-danger">مجمد</span>'; ?>
                            </td>
                            <td>
                              <form action="index.php?page=users&action=toggle-status" method="POST" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
                                <input type="hidden" name="id" value="<?= (int)$doc['id']; ?>">
                                <input type="hidden" name="is_active" value="<?= (int)$doc['is_active']; ?>">
                                <button type="submit" class="btn <?= $doc['is_active'] == 1 ? 'btn-warning' : 'btn-success'; ?> btn-sm">
                                  <i class="fas <?= $doc['is_active'] == 1 ? 'fa-user-slash' : 'fa-user-check'; ?>"></i>
                                  <?= $doc['is_active'] == 1 ? 'تجميد الحساب' : 'تنشيط الحساب'; ?>
                                </button>
                              </form>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>

                <div class="tab-pane fade" id="patients-content" role="tabpanel">
                  <table class="table table-bordered table-striped text-center">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>حالة الحساب</th>
                        <th>الإجراء</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(empty($patients)): ?>
                        <tr><td colspan="5" class="text-muted">لا يوجد مرضى مسجلين حالياً.</td></tr>
                      <?php else: ?>
                        <?php foreach($patients as $index => $pat): ?>
                          <tr>
                            <td><?= $index + 1; ?></td>
                            <td><b><?= sanitize($pat['name']); ?></b></td>
                            <td><?= sanitize($pat['email']); ?></td>
                            <td>
                              <?= $pat['is_active'] == 1 
                                ? '<span class="badge badge-success">نشط</span>' 
                                : '<span class="badge badge-danger">مجمد</span>'; ?>
                            </td>
                            <td>
                              <form action="index.php?page=users&action=toggle-status" method="POST" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
                                <input type="hidden" name="id" value="<?= (int)$pat['id']; ?>">
                                <input type="hidden" name="is_active" value="<?= (int)$pat['is_active']; ?>">
                                <button type="submit" class="btn <?= $pat['is_active'] == 1 ? 'btn-warning' : 'btn-success'; ?> btn-sm">
                                  <i class="fas <?= $pat['is_active'] == 1 ? 'fa-user-slash' : 'fa-user-check'; ?>"></i>
                                  <?= $pat['is_active'] == 1 ? 'تجميد الحساب' : 'تنشيط الحساب'; ?>
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
      </div>

    </div>
  </section>
</div>

<?php
require_once __DIR__ . '/../partials/footer.php';
?>