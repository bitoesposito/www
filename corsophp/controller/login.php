<?php

if (!empty($_POST)) {

  if (!empty($_POST['action']) && $_POST['action'] === 'logout') {
    // Distruggi tutte le variabili di sessione
    $_SESSION = array();

    // Se è stato impostato un cookie di sessione, distruggilo
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }

    // Distruggi la sessione
    session_destroy();
    
    header('Location: ../login.php');
    exit;
  }

  $token = $_POST['csrf'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  require_once '../functions.php';
  $result = verifyLogin($email, $password, $token);

  unset($_SESSION['csrf']);

  if ($result['success']) {

    // Regenerate session ID for security
    session_regenerate_id(true);

    unset($result['user']['password']);
    
    // Set session data after successful login
    $_SESSION['logged'] = true;
    $_SESSION['userData'] = $result['user'];
    header('Location: ../index.php');
    exit;

  } else {
    $_SESSION['message'] = $result['message'];
    header('Location: ../login.php?error=' . urlencode($result['message']));
    exit;
  }
} else {
  header('Location: ../login.php');
}
