<?php

$params = [
  'orderBy' => $orderBy,
  'orderDir' => $orderDir,
  'recordsPerPage' => $recordsPerPage,
  'search' => $search,
  'page' => $currentPage
];

$totalRecords = getTotaUsersCount($search);
$users = $totalRecords ? getUsers($params) : [];

require 'components/userList.php';