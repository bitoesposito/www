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
          $avatarPath = $_POST['oldAvatar'];
      
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
              redirectWithParams(); // Ritorna alla pagina con errori
          }
      
          // Gestione del caricamento di un nuovo avatar (se presente)
          if (!empty($_FILES['avatar']['name'])) {
              // Validazione del file caricato
              $fileErrors = validateFileUpload($_FILES['avatar']);
              if (!empty($fileErrors)) {
                  setFlashMessage(implode('<br>', $fileErrors));
                  redirectWithParams(); // Torna indietro se errore nel file
              }
      
              // Upload dell'immagine avatar
              $res = handleAvatarUpload($_FILES['avatar'], $id);
              if ($res) {
                  $avatarPath = $res; // Aggiorna il path se l'upload ha successo
              }
          }
      
          // Assegna il percorso dell’avatar al dataset finale
          $userData['avatar'] = $avatarPath;
      
          // Aggiornamento dell'utente nel database
          $res = updateUser($userData, $id);
      
          // Impostazione del messaggio di feedback nella sessione
          $_SESSION['message'] = $res ? 'USER ' . $id . ' UPDATED' : 'ERROR UPDATING USER ' . $id;
          $_SESSION['messageType'] = $res ? 'success' : 'danger';
      
          // Ricostruzione dei parametri della query (senza `id` e `action`)
          $params = $_GET;
          unset($params['id'], $params['action']);
          $queryString = http_build_query($params);
      
          // Redirect alla pagina principale con i parametri di paginazione
          header('Location:../index.php?' . $queryString);
          break;

    case 'delete':
        // Gestione dell'eliminazione di un utente
        $id = (int)getParam('id', 0);
        $res = deleteUser($id);

        // Gestione del messaggio di risposta
        $message = $res ? 'USER ' . $id . ' deleted' : ' error deleting user' . $id;
        $_SESSION['message'] = $message;
        $_SESSION['messageType'] = $res ? 'success' : 'danger';

        // Redirect alla pagina principale
        $params = $_GET;
        unset($params['id'], $params['action']);
        $queryString = http_build_query($params);
        header('Location:../index.php?' . $queryString);
        break;

    default:
        // Nessuna azione specificata
        break;
}
