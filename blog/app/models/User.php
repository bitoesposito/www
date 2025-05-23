<?php

namespace App\Models;

use PDO;

class User {

  public function __construct(protected PDO $conn) {}

  public function getUserByEmail(string $email) {

    /**
     * @var $conn mysqli
     */
    $result = [];
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
      $result;
    }

    $sql = "SELECT *  FROM users WHERE email = :email ";
    $stm = $this->conn->prepare($sql);
    $stm->execute(['email' => $email]);

    if ($stm) {
      $result = $stm->fetch(PDO::FETCH_ASSOC);
    }
    return $result;
  }

  public function saveUser(string $username, string $email, string $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stm = $this->conn->prepare($sql);
    $stm->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword]);
  }
}
