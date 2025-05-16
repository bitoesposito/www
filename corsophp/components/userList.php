<?php
$nextOrderDir = $orderDir === 'ASC' ? 'DESC' : 'ASC';
$orderDirClass = $orderDir;

// Separate parameters for table headers (with next order direction) and pagination (with current order direction)
$headerParams = "search=$search&recordsPerPage=$recordsPerPage&orderDir=$nextOrderDir";
$paginationParams = "search=$search&recordsPerPage=$recordsPerPage&orderDir=$orderDir&orderBy=$orderBy";

$baseUrl = "?$paginationParams";

$maxLinks = getConfig('maxLinks', 10);
?>

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

<table class="table table-dark table-striped">
  <thead id="userList">
    <tr>

      <th class="<?= $orderBy === 'id' ? $orderDirClass : '' ?>">
        <a href="?<?= $headerParams ?>&orderBy=id">ID</a>
      </th>
      <th class="<?= $orderBy === 'username' ? $orderDirClass : '' ?>">
        <a href="?<?= $headerParams ?>&orderBy=username">NAME</a>
      </th>
      <th class="<?= $orderBy === 'fiscalcode' ? $orderDirClass : '' ?>">
        <a href="?<?= $headerParams ?>&orderBy=fiscalcode">FISCAL CODE</a>
      </th>
      <th class="<?= $orderBy === 'email' ? $orderDirClass : '' ?>">
        <a href="?<?= $headerParams ?>&orderBy=email">EMAIL</a>
      </th>
      <th class="<?= $orderBy === 'age' ? $orderDirClass : '' ?>">
        <a href="?<?= $headerParams ?>&orderBy=age">AGE</a>
      </th>
      <th>&nbsp;</th>

    </tr>
  </thead>

  <tbody>
    <?php
    if ($users) {
      foreach ($users as $user) { ?>

        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= $user['username'] ?></td>
          <td><?= $user['fiscalcode'] ?></td>
          <td><a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></td>
          <td><?= $user['age'] ?></td>
          <td>
            <div class="d-flex gap-2">
              <a
                href="?action=update&id=<?= $user['id'] ?>&<?= $paginationParams ?>"
                class="btn btn-outline-primary">
                <i class="fa fa-pen fs-6"></i>
              </a>
              <a
                onclick="return confirm('Are you sure you want to delete this user?')"
                href="<?= $updateUrl ?>?action=delete&id=<?= $user['id'] ?>&<?= $paginationParams ?>"
                class="btn btn-outline-secondary">
                <i class="fa fa-trash fs-6"></i>
              </a>
            </div>
          </td>
        </tr>

      <?php
      }
    } else { ?>

      <tr>
        <td class="text-center" colspan="5">No records found</td>
      </tr>

    <?php
    }
    ?>
  </tbody>

</table>

<?php
require 'pagination.php';
echo createPagination($totalRecords, $recordsPerPage, $currentPage, $baseUrl, $maxLinks);
?>