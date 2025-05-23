<?php
$currenturl = $_SERVER['PHP_SELF'];
$indexPage = '/blog';
$action = $_GET['action'] ?? '';
$indexActive = !$action ? 'active' : '';
?>

<!-- Fixed navbar -->
<header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark border-bottom border-body">
    <div class="container d-flex flex-nowrap align-items-center">
      <a class="navbar-brand"
        href="<?= $indexPage ?>">
        <h3><i><b>UMS blog</b></i></h3>
      </a>
      <div class="d-flex w-100 justify-content-between">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a <?php if(isUserLoggedin()) : ?> href="/blog/create" <?php else: ?> href="/blog/auth/login" <?php endif; ?> class="nav-link"><i class="fa fa-plus"></i> New post</a>
          </li>
        </ul>
        <div class="d-flex gap-2">
          <div class="d-flex flex-column">
            <?php 
            if(isUserLoggedin()) : ?>
              <p class="lh-1 mb-0 text-muted">
              Logged in as:<br>
              <a href="/blog/auth/logout" id="logout" class="fw-semibold text-uppercase text-decoration-none">
                <i class="fa fa-sign-out" style="font-size: 0.8rem;"></i> <?= $_SESSION['userData']['username'] ?? 'Unknown User' ?>
              </a>
            </p>
            <?php else: ?>
             <div class="d-flex gap-2">
              <a href="/blog/auth/login" class="btn btn-outline-primary">Login</a>
              <a href="/blog/auth/signup" class="btn btn-primary">Signup</a>
             </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>