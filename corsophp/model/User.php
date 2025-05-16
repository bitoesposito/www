<?php
declare(strict_types=1);

function deleteUser(int $id) : bool {
  require_once '../connection.php';
  $conn = getConnection();
  $sql = 'DELETE FROM users WHERE id='.$id;
  $res = $conn->query($sql);
  return $res && $conn->affected_rows;
}