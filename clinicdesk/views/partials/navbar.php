<nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow-sm py-2 px-3">
    <div class="container-fluid">
        <span class="navbar-text fw-bold text-dark fs-5">
            <i class="fa-solid fa-laptop-medical text-primary me-2"></i> لوحة تحكم عيادة ClinicDesk
        </span>
        <div class="ms-auto d-flex align-items-center">
            <?php if (Auth::check()): $curr = Auth::currentUser(); ?>
                <span class="badge bg-light text-dark border p-2 me-3 fs-6">
                    <i class="fa-solid fa-user-circle text-secondary me-1"></i>
                    <?php echo $curr['name']; ?> (<?php echo ucfirst($curr['role']); ?>)
                </span>
                <a href="index.php?page=auth&action=logout" class="btn btn-outline-danger btn-sm fw-bold">
                    <i class="fa-solid fa-sign-out-alt"></i> خروج
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
