<?php
$currenturl = $_SERVER['PHP_SELF'];
$indexPage = '/blog';
$action = $_GET['action'] ?? '';
$indexActive = !$action ? 'active' : '';
?>

<!-- Fixed navbar -->
<header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark border-bottom border-body">
    <div class="container align-items-center">
      <a class="navbar-brand"
        href="<?= $indexPage ?>">
        <h3><i><b>UMS blog</b></i></h3>
      </a>
      <div class="collapse navbar-collapse justify-content-between">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="/blog/create"><i class="fa fa-plus"></i> New post</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Disabled</a>
          </li>
        </ul>
        <form class="form-inline mt-2 mt-md-0 d-flex gap-2">
          <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>
</header>