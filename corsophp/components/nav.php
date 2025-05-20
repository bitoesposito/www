<?php
$currenturl = $_SERVER['PHP_SELF'];
$indexPage = 'index.php';
$action = $_GET['action'] ?? '';
$indexActive = !$action ? 'active' : '';
?>

<!-- Fixed navbar -->
<header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark border-bottom border-body">
    <div class="container align-items-center">
      <a class="navbar-brand"
      href="<?= $indexPage ?>">
        <h3><i><b>UMS php</b></i></h3>
      </a>
      <button
        class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0 w-100" >
          <li class="nav-item">
            <a class="text-nowrap nav-link active" aria-current="page" href="<?= $indexPage ?>"><i class="fa-solid fa-users fa-xs me-1"></i> Users</a>
          </li>
          <li class="nav-item">
            <a class="text-nowrap nav-link <?= $indexActive ?>" href="<?= $indexPage ?>?action=insert"><i class="fa-solid fa-user-plus fa-xs me-1"></i>New user</a>
          </li>
          <form role="form" method="post" action="controller/login.php" class="w-100 d-flex justify-content-start justify-content-md-end">
            <input type="hidden" name="action" value="logout">
            <button type="submit" class="nav-link" href="login.php"><i class="fa-solid fa-arrow-right-from-bracket fa-xs me-1"></i>Log out</button>
          </form>
          
      </div>

      <?php
      if (!empty($_SESSION['message'])) {

        $message = strtolower($_SESSION['message']);
        $success = $_SESSION['success'] ?? true;
        $alertType = $message['messageType'] ?? 'success';

        require_once './components/message.php';

        unset($_SESSION['message']);
        unset($_SESSION['success']);
      }
      ?>
    </div>
  </nav>
</header>