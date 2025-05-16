<?php
$nextOrderDir = $orderDir === 'ASC' ? 'DESC' : 'ASC';
$orderDirClass = $orderDir;

// Separate parameters for table headers (with next order direction) and pagination (with current order direction)
$headerParams = "search=$search&recordsPerPage=$recordsPerPage&orderDir=$nextOrderDir";
$paginationParams = "search=$search&recordsPerPage=$recordsPerPage&orderDir=$orderDir&orderBy=$orderBy";

$baseUrl = "?$paginationParams";

$maxLinks = getConfig('maxLinks', 10);
?>

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
  echo createPagination($totalRecords, $recordsPerPage, $currentPage , $baseUrl, $maxLinks);
?>