<?php

session_start();
require_once '../functions.php';

$header = strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
if(!empty($_POST) && $header === 'XMLHttpRequest') {
  $token = $_POST['csrf'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  $result = verifyLogin($email, $password, $token);
  echo json_encode($result);
}