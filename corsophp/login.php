<?php
session_start();
require_once 'functions.php';

if (isUserLogged()) {
  header('Location: index.php');
  exit;
}

$bytes = random_bytes(32);
$token = bin2hex($bytes);
$_SESSION['csrf'] = $token;

include_once './components/head.php';
?>

<section class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 80px);">
  <div id="login-form" class="w-100" style="max-width: 400px;">

    <h2>Login</h2>

    <form class="d-flex flex-column gap-2" action="controller/login.php" method="POST">

      <input type="hidden" name="csrf" value="<?= $token ?>">

      <div class="form-group">
        <label for="email">Email address</label>
        <input required type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input required type="password" class="form-control" id="password" name="password" placeholder="Password">
      </div>

      <div class="form-group form-check">
        <input style="cursor: pointer;" type="checkbox" class="form-check-input" id="remember">
        <label style="cursor: pointer;" class="form-check-label" for="remember">Remember me</label>
      </div>

      <?php
      if (!empty($_SESSION['message'])) {
        echo '<div class="alert alert-danger p-2">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
      }
      ?>

      <button type="submit" class="btn btn-primary">Login</button>

    </form>
  </div>
</section>

<?php
include_once './components/footer.php';
?>

<script>
  $(function() {
    $('form').on('submit', function(e) {
      e.preventDefault();
      const data = $(this).serialize();
      
      $.ajax({
        method: 'POST',
        data: data,
        url: 'controller/login.php',
        success: function(response) {
          if (response.success) {
            location.href = 'index.php';
          } else {
            alert(response.message);
          }
        },
        error: function() {
          alert('Error contacting server');
        }
      });
    });
  });
</script>