<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - ClinicDesk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <style>
        body { background-color: #e9ecef; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-card { width: 100%; max-width: 420px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; background: #fff; padding: 30px; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <h2 class="text-primary fw-bold">ClinicDesk</h2>
        <p class="text-muted">تسجيل الدخول الآمن للنظام</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger font-weight-bold">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php include __DIR__ . '/../partials/alerts.php'; ?>

    <form action="index.php?page=auth&action=login" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
        
        <div class="mb-3">
            <label for="email" class="form-label fw-bold text-dark">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" class="form-control text-start" required autocomplete="email" placeholder="example@clinic.local">
        </div>
        
        <div class="mb-4">
            <label for="password" class="form-label fw-bold text-dark">كلمة المرور</label>
            <input type="password" name="password" id="password" class="form-control text-start" required placeholder="••••••••">
        </div>
        
        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold fs-5 shadow-sm">
            <i class="fa-solid fa-sign-in-alt me-1"></i> دخول للنظام
        </button>
    </form>
</div>

</body>
</html>
