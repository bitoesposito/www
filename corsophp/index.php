<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

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

    <?php

    $action = getParam('action');

    switch ($action) {
      case 'update':
        require_once 'components/userForm.php';
        break;
    
      default:
        require_once './controller/displayUsers.php';
        break;
    }

    ?>

  </div>
</main>

<?php
require_once 'components/footer.php';
?>