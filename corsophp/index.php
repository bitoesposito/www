<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'functions.php';

$page = $_SERVER['PHP_SELF'];

// records per page
$recordsPerPageOptions = getConfig('recordsPerPageOptions', [5 ,10 ,20]);
$recordsPerPageDefault = getConfig ('recordsPerPage', 10);
$recordsPerPage = (int)getParam('recordsPerPage', $recordsPerPageDefault);

// search
$search = getParam('search', '');
$search = strip_tags($search);

// order
$orderDir = getParam('orderDir', 'DESC');
$orderBy = getParam('orderBy', 'id');
if (!in_array($orderBy, getConfig('orderByColums'))) {
  $orderBy = 'id';
}

require_once 'components/head.php';
require_once 'components/nav.php';
?>

<!-- Begin page content -->
<main class="flex-shrink-0 mt-5">
  <div class="container">
    <div class="w-100 d-flex justify-content-between align-items-center">
      <h2>Cerca un utente</h2>

      <form style="margin: 0;" role="search" id="searchForm" method="GET" class="d-flex gap-2 align-items-center">
        <input type="hidden" name="orderBy" value="<?=$orderBy?>">
        <input type="hidden" name="orderDir" value="<?=$orderDir?>">

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
        
        <input type="search" name="search" id="" class="form-control me-2" value="<?=$search?>" placeholder="Cerca un utente...">
        <a href="<?=$page?>" class="btn btn-outline-secondary"><i class="fa fa-repeat" aria-hidden="true"></i></a>
      </form>

    </div>

    <?php
      $action = getParam('action');
      
      switch ($action) {
        default:

          $params = [
            'orderBy' => $orderBy,
            'orderDir' => $orderDir,
            'recordsPerPage' => $recordsPerPage,
            'search' => $search
          ];

          $users = getUsers($params);

          require 'components/userList.php';
          break;
      }
    ?>

    <p class="lead">Pin a footer to the bottom of the viewport in desktop browsers with this custom HTML and CSS. A
      fixed navbar has been added with <code class="small">padding-top: 60px;</code> on the <code
        class="small">main &gt; .container</code>.</p>
    <p>Back to <a href="#">the default sticky footer</a> minus
      the navbar.</p>
  </div>
</main>

<?php
require_once 'components/footer.php';
?>