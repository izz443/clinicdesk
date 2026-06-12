<?php if (isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash']['type']; ?> alert-dismissible fade show shadow-sm fw-bold" role="alert">
        <?php if ($_SESSION['flash']['type'] === 'success'): ?>
            <i class="fa-solid fa-check-circle me-2"></i>
        <?php else: ?>
            <i class="fa-solid fa-exclamation-triangle me-2"></i>
        <?php endif; ?>
        <?php echo $_SESSION['flash']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
