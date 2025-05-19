<?php

/**
 * Controller per la gestione delle operazioni CRUD sugli utenti
 * Gestisce le operazioni di creazione, aggiornamento ed eliminazione degli utenti
 */

session_start();

// Inclusione delle dipendenze necessarie
require '../functions.php';
require '../model/User.php';
require_once '../functions.php';

// Recupero dell'azione da eseguire dai parametri GET
$action = getParam('action');

switch ($action) {
  case 'create':
    // Gestione della creazione di un nuovo utente
    $userData = [
      'username' => $_POST['username'],
      'email' => $_POST['email'],
      'fiscalcode' => $_POST['fiscalcode'],
      'age' => (int)$_POST['age'],
      'avatar' => null
    ];

    // Validazione dei dati utente
    $errors = validateUserData($userData);
    if ($errors) {
      setFlashMessage(implode(', ', $errors));
      redirectWithParams();
    }

    // Gestione dell'upload dell'avatar
    $avatarPath = '';
    if ($_FILES['avatar']['name'] && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
      // Validazione del file prima dell'upload
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

    // Gestione del messaggio di risposta
    $message = $res ? 'USER ' . $res . ' created' : 'error creating user';
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = $res ? 'success' : 'danger';

    // Redirect alla pagina principale
    $params = $_GET;
    unset($params['action']);
    $queryString = http_build_query($params);
    header('Location:../index.php?' . $queryString);
    break;

  case 'update':
    // Recupero dell'ID utente e dell'avatar precedente
    $id = (int)$_POST['id'];
    $oldAvatarPath = $_POST['oldAvatar'];

    // Raccolta dei dati aggiornati dal form
    $userData = [
      'id' => $id,
      'username' => trim($_POST['username']),
      'email' => trim($_POST['email']),
      'fiscalcode' => trim($_POST['fiscalcode']),
      'age' => (int)$_POST['age']
    ];

    // Validazione dei dati utente
    $errors = validateUserData($userData);
    if ($errors) {
      setFlashMessage(implode(',', $errors));
      redirectWithParams();
    }

    // Gestione del caricamento di un nuovo avatar (se presente)
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

    // Aggiornamento dell'utente nel database
    $res = updateUser($userData, $id);
    if (!$res) {
      // Se l'aggiornamento fallisce e abbiamo caricato una nuova immagine, 
      // eliminiamo la nuova immagine e ripristiniamo quella vecchia
      if (!empty($_FILES['avatar']['name'])) {
        deleteUserImages($userData['avatar']);
        $userData['avatar'] = $oldAvatarPath;
      }
      setFlashMessage('Error updating user');
      redirectWithParams();
    }

    // Impostazione del messaggio di feedback nella sessione
    setFlashMessage('User updated successfully', 'success');

    // Ricostruzione dei parametri della query (senza `id` e `action`)
    $params = $_GET;
    unset($params['id'], $params['action']);
    $queryString = http_build_query($params);
    header('Location: ../index.php?' . $queryString);
    exit;

  case 'delete':
    $id = (int)$_GET['id'];
    
    // Recupera il path dell'avatar prima di eliminare l'utente
    $user = getUserById($id);
    if ($user && $user['avatar']) {
      deleteUserImages($user['avatar']);
    }
    
    // Elimina l'utente dal database
    $res = deleteUser($id);
    if (!$res) {
      setFlashMessage('Error deleting user');
      redirectWithParams();
    }
    
    setFlashMessage('User deleted successfully', 'success');
    redirectWithParams();

  default:
    // Nessuna azione specificata
    break;
}
