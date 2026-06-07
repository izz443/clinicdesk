<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><span class="fas fa-bars"></span></a>
    </li>
  </ul>

  <ul class="navbar-nav mr-auto">
    <li class="nav-item">
      <form action="index.php?page=auth/logout" method="POST" class="form-inline">
        <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
        <button type="submit" class="btn btn-danger btn-sm">
          <span class="fas fa-sign-out-alt"></span> تسجيل الخروج
        </button>
      </form>
    </li>
  </ul>
</nav>