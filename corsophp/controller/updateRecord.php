<?php
session_start();

require '../functions.php';
require '../model/User.php';
require_once '../functions.php';

// definizione parametri
$action = getParam('action');

switch ($action) {
  case 'delete':

    $id = (int)getParam('id', 0);
    $res = deleteUser($id);
    $params = $_GET;
    unset($params['id'], $params['action']);

    $queryString = http_build_query($params);
    header('Location:../index.php?'.$queryString);

    $message = $res ? 'USER '.$id.' deleted':' error deleting user'.$id;
    $_SESSION['message'] = $message;
    $_SESSION['success'] = $res;
    
    break;
  case 'update':
    break;
  default:
    break;
}