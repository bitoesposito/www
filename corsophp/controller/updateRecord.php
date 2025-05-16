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
    $_SESSION['messageType'] = $res ? 'success' : 'danger';

    unset($params['id'], $params['action']);
    $queryString = http_build_query($params);
    header('Location:../index.php?'.$queryString);
    
    break;
  case 'update':

    $id = (int)$_POST['id'];
    $userData = [
      'id' => $id,
      'username' => $_POST['username'],
      'email' => $_POST['email'],
      'fiscalcode' => $_POST['fiscalcode'],
      'age' => (int)$_POST['age']
    ];
    
    $res = updateUser($userData, $id);
    
    $message = $res ? 'USER '.$id.' updated':' error updating user'.$id;
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = $res ? 'success' : 'danger';
    
    $params = $_GET;
    unset($params['id'], $params['action']);
    $queryString = http_build_query($params);
    header('Location:../index.php?'.$queryString);
    
    break;
  case 'create':
    
    $userData = [
      'username' => $_POST['username'],
      'email' => $_POST['email'],
      'fiscalcode' => $_POST['fiscalcode'],
      'age' => (int)$_POST['age']
    ];

    $res = createUser($userData);

    $message = $res ? 'USER '.$res.' created':' error creating user';
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = $res ? 'success' : 'danger';

    $params = $_GET;
    unset($params['action']);
    $queryString = http_build_query($params);
    header('Location:../index.php?'.$queryString);
    
    break;
  default:
    break;
}