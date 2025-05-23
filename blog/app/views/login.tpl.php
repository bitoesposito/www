<section class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 160px);">
  <div id="login-form" class="w-100" style="max-width: 400px;">

    <h2><?= $signup ? 'Sign up' : 'Login' ?></h2>

    <form class="d-flex flex-column gap-2" action="<?= $signup ? '/blog/auth/signup' : '/blog/auth/login' ?>" method="POST">

      <input type="hidden" name="csrf" value="<?= $token ?>">

      <?php if($signup) : ?>
      <div class="form-group">
        <label for="username">Username</label>
        <input required type="text" class="form-control" id="username" name="username" placeholder="Enter username">
      </div>
      <?php endif; ?>

      <div class="form-group">
        <label for="email">Email address</label>
        <input required type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input required type="password" class="form-control" id="password" name="password" placeholder="Password">
      </div>

      <?php if(!$signup) : ?>
      <div class="form-group form-check">
        <input style="cursor: pointer;" type="checkbox" class="form-check-input" id="remember">
        <label style="cursor: pointer;" class="form-check-label" for="remember">Remember me</label>
      </div>
      <?php endif; ?>

      <?php
      if (!empty($_SESSION['message'])) {
        echo '<div class="alert alert-danger p-2">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
      }
      ?>

      <button type="submit" class="btn btn-primary mt-2"><?= $signup ? 'Sign up' : 'Login' ?></button>

    </form>
  </div>
</section>