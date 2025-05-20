<?php

/**
 * Controller for managing CRUD operations on users
 * Handles user creation, update and deletion operations
 */

session_start();

// Include required dependencies
require '../functions.php';
require '../model/User.php';
require_once '../functions.php';

// Get action from GET parameters
$action = getParam('action');

switch ($action) {
  case 'create':
    // Handle new user creation
    $userData = [
      'username' => $_POST['username'],
      'email' => $_POST['email'],
      'fiscalcode' => $_POST['fiscalcode'],
      'age' => (int)$_POST['age'],
      'roletype' => $_POST['roletype'] ?? 'user',
      'avatar' => null
    ];

    // Validate user data
    $errors = validateUserData($userData);
    if ($errors) {
      setFlashMessage(implode(', ', $errors));
      redirectWithParams();
    }

    // Handle avatar upload
    $avatarPath = '';
    if ($_FILES['avatar']['name'] && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
      // Validate file before upload
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

    // Handle response message
    $message = $res ? 'User ' . $res . ' created successfully' : 'Error creating user';
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = $res ? 'success' : 'danger';

    // Redirect to main page
    $params = $_GET;
    unset($params['action']);
    $queryString = http_build_query($params);
    header('Location:../index.php?' . $queryString);
    break;

  case 'update':
    // Get user ID and previous avatar
    $id = (int)$_POST['id'];
    $oldAvatarPath = $_POST['oldAvatar'];

    // Collect updated data from form
    $userData = [
      'id' => $id,
      'username' => trim($_POST['username']),
      'email' => trim($_POST['email']),
      'password' => '',
      'roletype' => $_POST['roletype'] ?? 'user',
      'fiscalcode' => trim($_POST['fiscalcode']),
      'age' => (int)$_POST['age'],
    ];

    // Validate user data
    $errors = validateUserData($userData);
    if ($errors) {
      setFlashMessage(implode(',', $errors));
      redirectWithParams();
    }

    // Handle new avatar upload (if present)
    if (!empty($_FILES['avatar']['name'])) {
      $avatarPath = handleAvatarUpload($_FILES['avatar'], $id, $oldAvatarPath);
      if (!$avatarPath) {
        setFlashMessage('Error uploading avatar image');
        redirectWithParams();
      }
      $userData['avatar'] = $avatarPath;
    } else {
      $userData['avatar'] = $oldAvatarPath;
    }

    // Update user in database
    $res = updateUser($userData, $id);
    if (!$res) {
      // If update fails and we uploaded a new image,
      // delete the new image and restore the old one
      if (!empty($_FILES['avatar']['name'])) {
        deleteUserImages($userData['avatar']);
        $userData['avatar'] = $oldAvatarPath;
      }
      setFlashMessage('Error updating user');
      redirectWithParams();
    }

    // Set feedback message in session
    setFlashMessage('User updated successfully', 'success');

    // Rebuild query parameters (without `id` and `action`)
    $params = $_GET;
    unset($params['id'], $params['action']);
    $queryString = http_build_query($params);
    header('Location: ../index.php?' . $queryString);
    exit;

  case 'delete':
    $id = (int)$_GET['id'];
    
    // Get avatar path before deleting user
    $user = getUserById($id);
    if ($user && $user['avatar']) {
      deleteUserImages($user['avatar']);
    }
    
    // Delete user from database
    $res = deleteUser($id);
    if (!$res) {
      setFlashMessage('Error deleting user');
      redirectWithParams();
    }
    
    setFlashMessage('User deleted successfully', 'success');
    redirectWithParams();

  default:
    // No action specified
    break;
}
