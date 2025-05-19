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
    header('Location:../index.php?' . $queryString);

    $message = $res ? 'USER ' . $id . ' deleted' : ' error deleting user' . $id;
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = $res ? 'success' : 'danger';

    unset($params['id'], $params['action']);
    $queryString = http_build_query($params);
    header('Location:../index.php?' . $queryString);

    break;
  case 'update':

    $id = (int)$_POST['id'];
    $userData = [
      'id' => $id,
      'username' => $_POST['username'],
      'email' => $_POST['email'],
      'fiscalcode' => $_POST['fiscalcode'],
      'age' => (int)$_POST['age'],
      'avatar' => null
    ];

    try {
      // Prima gestiamo l'upload del file se presente
      $avatar = null;
      if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
        try {
          $avatar = handleAvatarUpload($_FILES['avatar']);
        } catch (Exception $e) {
          // Se c'è un errore nell'upload, interrompiamo tutto
          setFlashMessage($e->getMessage(), 'danger');
          redirectWithParams();
          return; // Importante: usciamo dalla funzione
        }
      }

      // Se abbiamo un nuovo avatar, aggiorna il record
      if ($avatar !== null) {
          $userData['avatar'] = $avatar;
      }
      
      $res = updateUser($userData, $id);

      $message = $res ? 'USER ' . $id . ' updated' : ' error updating user' . $id;
      $_SESSION['message'] = $message;
      $_SESSION['messageType'] = $res ? 'success' : 'danger';

      $params = $_GET;
      unset($params['id'], $params['action']);
      $queryString = http_build_query($params);
      header('Location:../index.php?' . $queryString);

    } catch (Exception $e) {
      setFlashMessage($e->getMessage(), 'danger');
      redirectWithParams();
    }

    break;
  case 'create':

    $userData = [
      'username' => $_POST['username'],
      'email' => $_POST['email'],
      'fiscalcode' => $_POST['fiscalcode'],
      'age' => (int)$_POST['age'],
      'avatar' => null
    ];
    $avatarPath = '';
    if($_FILES['avatar']['name'] && is_uploaded_file($_FILES['avatar']['tmp_name'])){
      // Validate file before uploading
      $errors = validateFileUpload($_FILES['avatar']);
      if (!empty($errors)) {
        setFlashMessage(implode(', ', $errors), 'danger');
        redirectWithParams();
        return;
      }
      $avatarPath = handleAvatarUpload($_FILES['avatar']);
    }
    $userData['avatar'] = $avatarPath;
    $res = createUser($userData);
   
    $message = $res ? 'USER ' . $res . ' CREATED' : 'ERROR CREATING USER ' ;
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = $res ? 'success' : 'danger';
    $params = $_GET;
    unset( $params['action']);
    $queryString = http_build_query($params);
    header('Location:../index.php?' . $queryString);
    break;

    break;
  default:
    break;
}
