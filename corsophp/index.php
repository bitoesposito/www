<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'functions.php';

$page = $_SERVER['PHP_SELF'];

// records per page
$recordsPerPageOptions = getConfig('recordsPerPageOptions', [5, 10, 20]);
$recordsPerPageDefault = getConfig('recordsPerPage', 10);
$recordsPerPage = (int)getParam('recordsPerPage', $recordsPerPageDefault);

$currentPage = (int) getParam('page', 1);

// search
$search = getParam('search', '');
$search = strip_tags($search);

// order
$orderDir = getParam('orderDir', 'ASC');
$orderBy = getParam('orderBy', 'id');
if (!in_array($orderBy, getConfig('orderByColums'))) {
  $orderBy = 'id';
}

// update user controller
$updateUrl = 'controller/updateRecord.php';

require_once 'components/head.php';
require_once 'components/nav.php';
?>

<!-- Begin page content -->
<main class="flex-shrink-0" style="margin-top:5rem;">
  <div class="container">
    <div class="w-100 d-flex justify-content-between align-items-center">
      <h2>Users list</h2>

      <form style="margin: 0;" role="search" id="searchForm" method="GET" class="d-flex gap-2 align-items-center">

        <label style="line-height: 1;" class="form-label mb-0" for="recordsPerPage">Records per page</label>
        <select style="width: 5rem;" class="form-select" name="recordsPerPage" onchange="document.forms.searchForm.submit()">

          <?php
          foreach ($recordsPerPageOptions as $v) {
            $v = (int) $v;
            $selected = $v === $recordsPerPage ? 'selected' : '';
            echo "<option $selected value='$v'>$v</option>\n";
          }
          ?>
        </select>

        <input type="search" name="search" class="form-control me-2" value="<?= $search ?>" placeholder="Search a user...">
        <a href="<?= $page ?>" class="btn btn-outline-secondary"><i class="fa fa-repeat" aria-hidden="true"></i></a>
      </form>

    </div>

    <?php

    $action = getParam('action');

    require_once './controller/displayUsers.php';
    ?>

  </div>
</main>

<?php
require_once 'components/footer.php';
?>