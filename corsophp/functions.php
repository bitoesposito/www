<?php

function getRandName() {
  $names = ['VITO', 'SAMANTHA', 'DARIO', 'EMANUELE'];
  $lastnames = ['ESPOSITO', 'STANCO', 'FALEO', 'NATALE'];

  $rand1 = mt_rand(0, count($names) - 1);
  $rand2 = mt_rand(0, count($lastnames) - 1);

  return $names[$rand1].' '.$lastnames[$rand2];
}

function getRandEmail($name) {
  $domains = ['google.com', 'libero.it', 'virgilio.it', 'italia.online'];
  $rand3 = mt_rand(0, count($domains) - 1);
  return strtolower(str_replace(' ','.',$name).mt_rand(10,99).'@'.$domains[$rand3]);
}

function getRandFiscalCode() {
  $i = 16;
  $res = '';
  while($i > 0 ) {
    $res .= chr(mt_rand(65,90));
    $i--;
  }
  return $res;
}

function getRandAge() {
  return mt_rand(0,120);
}

function insertRandUser(int $total, mysqli $conn) {
  while ($total > 0) {
    $username = getRandName();
    $email = getRandEmail($username);
    $fiscalcode = getRandFiscalCode();
    $age = getRandAge();

    $sql = 'INSERT INTO users (username, email, fiscalcode, age) VALUES ';
    $sql .= "('$username', '$email', '$fiscalcode', $age)";

    $res = $conn->query($sql);
      if(!$res) {
        echo $conn->error;
      } else {
        $total--;
      }
  }
}

require 'connection.php';

function getParam($param, $default = '') {
  return $_REQUEST[$param] ?? $default;
}

function getConfig($param) {
  // var_dump($GLOBALS);
  $config = require 'config.php';
  return $config[$param] ?? null;
}

function getUsers(array $params = []) {
  $conn = getConnection();

  $orderBy = $params['orderBy'] ?? 'id';
  $orderDir = $params['orderDir'] ?? 'DESC';
  $search = $params['search'] ?? '';
  $limit = (int)($params['recordsPerPage'] ?? 10);
  $page = $params['page'] ?? 1;

  $start = $limit * ($page - 1);

  $records = [];

  $sql = "SELECT * FROM users";
  if ($search) {
    $sql .= " WHERE";
    if(is_numeric($search)) {
      $sql .= " (id = $search OR age = $search) ";
    } else {
      $sql .= " (fiscalcode LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%')";
    }
  }
  $sql .= " ORDER BY $orderBy $orderDir LIMIT $start, $limit";
  $res = $conn->query($sql);
  
  if ($res) {  
    while ($row = $res->fetch_assoc()) {
      $records[] = $row;
    }
  }

  return $records;
}

function getTotaUsersCount(string $search = ''):int {
  $conn = getConnection();

  $sql = "SELECT COUNT(*) as total FROM users";
  if ($search) {
    $sql .= " WHERE";
    if(is_numeric($search)) {
      $sql .= " id = $search OR age = $search";
    } else {
      $search = $conn->real_escape_string($search);
      $sql .= " fiscalcode LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%'";
    }
  }

  $res = $conn->query($sql);
  
  if ($res && $row = $res->fetch_assoc()) {
    return (int) $row['total'];
  }

  return 0;
}

function dd(mixed $data = null) {
    var_dump($data);
    die;
}