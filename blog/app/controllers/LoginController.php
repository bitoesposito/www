<?php

namespace App\Controllers;
use App\Models\User;
use PDO;

class LoginController extends BaseController {

  public function __construct(
    protected \PDO $conn
  ) {
    parent::__construct($conn);
  }

  private function generateToken() {
    $bytes = random_bytes(32);
    $token = bin2hex($bytes);
    $_SESSION['csrf'] = $token;
    return $token;
  }

  public function showLogin() {
    $this->content = view('login',
    [
      'token' => $this->generateToken(),
      'signup' => false
    ]
  );
  }
  
  public function showSignup() {
    $this->content = view('login',
    [
      'token' => $this->generateToken(),
      'signup' => true
    ]
  );
  }

  public function login() {
    $token = $_POST['csrf'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $result = $this->verifyLogin($email, $password, $token);
    if ($result['success']) {
      session_regenerate_id();
      $_SESSION['loggedin'] = true;
      unset($result['user']['password']);
      $_SESSION['userData'] = $result['user'];
      header('Location: /blog/');
      exit;
    } else {
      $_SESSION['message'] = $result['message'];
      header('Location: /blog/auth/login');
    }
  }

  public function signup() {
    $token = $_POST['csrf'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $result = $this->verifySignup($username, $email, $password, $token);

    if ($result['success']) {

      $user = new User($this->conn);
      $user->saveUser($username, $email, $password);
      
      // Get the user data from database to set session
      $userData = $user->getUserByEmail($email);
      
      session_regenerate_id();
      $_SESSION['loggedin'] = true;
      unset($userData['password']); // Remove password from session data
      $_SESSION['userData'] = $userData;
      header('Location: /blog/');
      exit;

    } else {

      $_SESSION['message'] = $result['message'];
      header('Location: /blog/auth/login');

    }
  }

  public function logout() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
      );
    }
    
    // Destroy the session
    session_destroy();
    
    // Redirect to home page
    header('Location: /blog/');
    exit;
  }

  private function verifyLogin($email, $password, $token) {

    $result = [
        'message' => 'User logged in',
        'success' => true
    ];

    if ($token !== $_SESSION['csrf']) {
        $result = [
            'message' => 'Token mismatch',
            'success' => false
        ];
        return $result;
    }

    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $result = [
            'message' => 'Wrong email',
            'success' => false
        ];
        return $result;
    }

    if (strlen($password) < 6) {
        $result = [
            'message' => 'Password too small',
            'success' => false
        ];
        return $result;
    }

    $user = new User($this->conn);
    $resEmail = $user->getUserByEmail($email);

    if (!$resEmail) {
        $result = [
            'message' => 'User not found',
            'success' => false

        ];
        return $result;
    }
    
    if (!password_verify($password, $resEmail['password'])) {
        $result = [
            'message' => 'Wrong password',
            'success' => false

        ];
        return $result;
    }
    $result['user'] = $resEmail;
    return $result;
  }

  private function verifySignup($username, $email, $password, $token) {

    $result = [
      'message' => 'User signde up',
      'success' => true
    ];

    if ($token !== $_SESSION['csrf']) {
      $result = [
          'message' => 'Token mismatch',
          'success' => false
      ];
      return $result;
    }

    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email) {
      $result = [
        'message' => 'Wrong email',
        'success' => false
      ];
      return $result;
    }

    if (strlen($password) < 6) {
      $result = [
        'message' => 'Password too small',
        'success' => false
      ];
      return $result;
    }

    $user = new User($this->conn);
    $resEmail = $user->getUserByEmail($email);

    if ($resEmail) {
      $result = [
        'message' => 'Email already exists',
        'success' => false

      ];
      return $result;
    }

    if (strlen($username) < 4) {
      $result = [
        'message' => 'Username too small',
        'success' => false
      ];
      return $result;
    }
    
    return $result;
  }
  
}