<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ClinicDesk | تسجيل الدخول</title>
  <link rel="stylesheet" href="public/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="public/assets/adminlte/dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>Clinic</b>Desk</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">قم بتسجيل الدخول لبدء جلستك وحماية بياناتك</p>

      <?php if ($err = flash('error')): ?>
        <div class="alert alert-danger text-right" role="alert">
          <?= sanitize($err); ?>
        </div>
      <?php endif; ?>

      <form action="index.php?page=auth/login&action=submit" method="POST">
        <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">

        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control text-right" placeholder="البريد الإلكتروني" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control text-right" placeholder="كلمة المرور" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">تسجيل الدخول</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="public/assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="public/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/assets/adminlte/dist/js/adminlte.min.js"></script>
</body>
</html>