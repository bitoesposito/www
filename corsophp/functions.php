<?php

function getRandName()
{
  $names = ['VITO', 'SAMANTHA', 'DARIO', 'EMANUELE'];
  $lastnames = ['ESPOSITO', 'STANCO', 'FALEO', 'NATALE'];

  $rand1 = mt_rand(0, count($names) - 1);
  $rand2 = mt_rand(0, count($lastnames) - 1);

  return $names[$rand1] . ' ' . $lastnames[$rand2];
}

function getRandEmail($name)
{
  $domains = ['google.com', 'libero.it', 'virgilio.it', 'italia.online'];
  $rand3 = mt_rand(0, count($domains) - 1);
  return strtolower(str_replace(' ', '.', $name) . mt_rand(10, 99) . '@' . $domains[$rand3]);
}

function getRandFiscalCode()
{
  $i = 16;
  $res = '';
  while ($i > 0) {
    $res .= chr(mt_rand(65, 90));
    $i--;
  }
  return $res;
}

function getRandAge()
{
  return mt_rand(0, 120);
}

function insertRandUser(int $total, mysqli $conn)
{
  while ($total > 0) {
    $username = getRandName();
    $email = getRandEmail($username);
    $fiscalcode = getRandFiscalCode();
    $age = getRandAge();

    $sql = 'INSERT INTO users (username, email, fiscalcode, age) VALUES ';
    $sql .= "('$username', '$email', '$fiscalcode', $age)";

    $res = $conn->query($sql);
    if (!$res) {
      echo $conn->error;
    } else {
      $total--;
    }
  }
}

require 'connection.php';

function getParam($param, $default = '')
{
  return $_REQUEST[$param] ?? $default;
}

function getConfig($param)
{
  // var_dump($GLOBALS);
  $config = require 'config.php';
  return $config[$param] ?? null;
}

function getUsers(array $params = [])
{
  $conn = getConnection();

  $orderBy = $params['orderBy'] ?? 'id';
  $orderDir = $params['orderDir'] ?? 'ASC';
  $search = $params['search'] ?? '';
  $limit = (int)($params['recordsPerPage'] ?? 10);
  $page = $params['page'] ?? 1;

  $start = $limit * ($page - 1);

  $records = [];

  $sql = "SELECT * FROM users";
  if ($search) {
    $sql .= " WHERE";
    if (is_numeric($search)) {
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

function getTotaUsersCount(string $search = ''): int
{
  $conn = getConnection();

  $sql = "SELECT COUNT(*) as total FROM users";
  if ($search) {
    $sql .= " WHERE";
    if (is_numeric($search)) {
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

function dd(mixed $data = null)
{
  var_dump($data);
  die;
}

function showSessionMsg()
{
  if (!empty($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
    $alertType = $_SESSION['messageType'];
    unset($_SESSION['messageType']);
    require_once 'view/message.php';
  }
}

function handleAvatarUpload(array $file, int $userId = null): ?string
{

  $config = require 'config.php';
  $uploadDir = $config['uploadDir'] ?? 'avatar';
  $uploadDirPath = realpath(__DIR__) . '/' . $uploadDir . '/';
  $mimeMap = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif'
  ];
  $fileinfo = new finfo(FILEINFO_MIME_TYPE);
  $mimeType = $fileinfo->file($file['tmp_name']);
  //$extension = pathinfo($file['name']);
  $extension = $mimeMap[$mimeType];
  $fileName = ($userId ? $userId . '_' : '') . bin2hex(random_bytes(8)) . '.' . $extension;
  $res = move_uploaded_file($file['tmp_name'], $uploadDirPath . $fileName);
  return $res ? $uploadDir . '/' . $fileName : null;
}

function validateFileUpload(array $file): array
{
  $errors = [];

  if ($file['error'] !== UPLOAD_ERR_OK) {
    $errors[] = getUploadError($file['error']);
    return $errors;
  }
  $config = require 'config.php';

  $fileinfo = new finfo(FILEINFO_MIME_TYPE);
  $mimeType = $fileinfo->file($file['tmp_name']);
  if (!in_array($mimeType, $config['mimeTyped'] ?? ['image/jpeg'])) {
    $errors[] = 'Invalid file type.Allowed types: ' . implode(',', $config['mimeTypes']);
  }
  if ($file['size'] > $config['maxFileSize']) {
    $errors[] = 'File size exceeds ' . $config['maxFileSize'];
  }
  
  return $errors;
}

function getUploadError(int $errorCode): string
{
  $error = '';

  switch ($errorCode) {
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:

      $error = 'File size exceeds the allowed limit.';
      break;
    case UPLOAD_ERR_PARTIAL:
      $error  = 'The file was only partially uploaded.';
      break;
    case UPLOAD_ERR_NO_FILE:
      $error  = 'No file was uploaded.';
      break;
    case UPLOAD_ERR_NO_TMP_DIR:
      $error  = 'Missing temporary folder.';
      break;
    case UPLOAD_ERR_CANT_WRITE:
      $error = 'Failed to write file to disk.';
      break;
    case UPLOAD_ERR_EXTENSION:
      $error  = 'File upload stopped by extension.';
      break;
    default:
      $error = 'Unknown file upload error.';
      break;
  }
  return $error;
}

function setFlashMessage(string $message, string $type = 'info')
{
  $_SESSION['message'] = $message;
  $_SESSION['messageType'] = $type;
}

function redirectWithParams(): void
{
  $params = $_GET;
  if (isset($params['id']))
    unset($params['id']);
  if (isset($params['action'])) {
    unset($params['action']);
  }
  $queryString = http_build_query($params);
  header('Location:../index.php?' . $queryString);
  exit;
}

function convertMaxUploadSizeToBytes():int
{
  $maxUploadSize = ini_get('upload_max_filesize');
  $number =(int) $maxUploadSize;
  $unit = strtoupper(substr($maxUploadSize, -1));
  switch($unit){
    case 'K':
      $number *= 1024;
      break;
    case 'M':
      $number *= (1024**2);
      break;
    case 'G':
      $number *= (1024**3);
      break;
    default:
      $number = (int) $maxUploadSize;
      break;
  }
  return $number;
}

function formatBytes(int $bytes): string
{
  $units = ['B', 'KB', 'MB', 'GB'];
  $power = floor(log($bytes, 1024));
  $number = round($bytes / (1024 ** $power), 2);
  return $number.' '.$units[$power];
  
}

function validateUserData(array $data): array
{
  $errors = [];

  if (empty($data['username']) || strlen($data['username']) > 64 || strlen($data['username']) < 3) {
    $errors['username'] = 'Username must be between 3 and 64 characters';
  }

  if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email address';
  }

  if (empty($data['fiscalcode']) || strlen($data['fiscalcode']) !== 16) {
    $errors['fiscalcode'] = 'Fiscal code must be 16 characters long';
  }

  if (!is_numeric($data['age']) || $data['age'] < 18 || $data['age'] > 120) {
    $errors['age'] = 'Age must be a number between 18 and 120';
  }



  return $errors;
}