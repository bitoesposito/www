<?php
declare(strict_types=1);

function deleteUser(int $id) : bool {
  require_once '../connection.php';
  $conn = getConnection();
  $sql = 'DELETE FROM users WHERE id = ?';
  $stm = $conn->prepare($sql);
  $stm->bind_param('i', $id);
  $res = $stm->execute();
  $affected = $stm->affected_rows;
  $stm->close();
  return $res && $affected > 0;
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
  $sql = 'UPDATE users SET username=?, email=?, fiscalcode=?, age=?, avatar=? WHERE id=?';
  $stm = $conn->prepare($sql);
  $stm->bind_param('sssisi',
    $data['username'],
    $data['email'],
    $data['fiscalcode'],
    $data['age'],
    $data['avatar'],
    $id
  );
  $res = $stm->execute();
  $stm->close();
  return $res;
}

function createUser(array $data) : int {
  require_once '../connection.php';
  $conn = getConnection();
  $sql = 'INSERT INTO users (username, email, fiscalcode, age, avatar) values(?,?,?,?,?)';
  $stm = $conn->prepare($sql);
  $stm->bind_param('sssis',
    $data['username'],
    $data['email'],
    $data['fiscalcode'],
    $data['age'],
    $data['avatar']
  );
  $res = $stm->execute();
  $stm->close();
  return $conn->insert_id;
}