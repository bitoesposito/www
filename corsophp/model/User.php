<?php
declare(strict_types=1);

function deleteUser(int $id) : bool {
  require_once '../connection.php';
  $conn = getConnection();
  $sql = 'DELETE FROM users WHERE id='.$id;
  $res = $conn->query($sql);
  return $res && $conn->affected_rows;
}

function getUserById(int $id) : array {
  $conn = getConnection();
  $sql = 'SELECT * FROM users WHERE id=?';
  $stm = $conn->prepare($sql);
  $stm->bind_param('i', $id);
  $stm->execute();
  $result = $stm->get_result();
  $user = $result->fetch_assoc();
  $stm->close();
  return $user;
}

function updateUser(array $data, int $id) : bool {
  require_once '../connection.php';
  $conn = getConnection();
  $sql = 'UPDATE users SET username=?, email=?, fiscalcode=?, age=? WHERE id=?';
  $stm = $conn->prepare($sql);
  $stm->bind_param('sssii',
    $data['username'],
    $data['email'],
    $data['fiscalcode'],
    $data['age'],
    $id);
  $res = $stm->execute();
  $stm->close();
  return $res;
}

function createUser(array $data) : int {
  require_once '../connection.php';
  $conn = getConnection();
  $sql = 'INSERT INTO users (username, email, fiscalcode, age) values(?,?,?,?)';
  $stm = $conn->prepare($sql);
  $stm->bind_param('sssi',
    $data['username'],
    $data['email'],
    $data['fiscalcode'],
    $data['age']
  );
  $res = $stm->execute();
  $stm->close();
  return $conn->insert_id;
}