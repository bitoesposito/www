<?php
declare(strict_types=1);

function deleteUser(int $id) : bool {
  require_once '../connection.php';
  $conn = getConnection();
  $sql = 'DELETE FROM users WHERE id = ?';
  $stm = $conn->prepare($sql);
  $stm->bind_param('i', $id);
  $res = $stm->execute();
  $affected = $stm->affected_rows;
  $stm->close();
  return $res && $affected > 0;
}

function getUserById(int $id) : array {
  $conn = getConnection();
  $sql = 'SELECT * FROM users WHERE id=?';
  $stm = $conn->prepare($sql);
  $stm->bind_param('i', $id);
  $stm->execute();
  $result = $stm->get_result();
  $user = $result->fetch_assoc();
  $stm->close();
  return $user;
}

function getUserByEmail($email) : array {
  $conn = getConnection();
  $res = [];
  $email = filter_var($email, FILTER_VALIDATE_EMAIL);

  if(!$email) {
    return $res;
  }
  
  $email = mysqli_escape_string($conn, $email);
  $sql = "SELECT * FROM users WHERE email='$email'";
  $res = $conn->query($sql);
  $user = $res->fetch_assoc();
  $conn->close();
  
  return $user ?: [];
}

function updateUser(array $data, int $id) : bool {
  require_once '../connection.php';
  $conn = getConnection();
  
  // Build the SQL query dynamically based on provided fields
  $sql = "UPDATE users SET username=?, email=?";
  $types = "ss";
  $params = [$data['username'], $data['email']];
  
  // Add password if provided or use default
  $password = $data['password'] ?? 'testuser';
  $sql .= ", password=?";
  $types .= "s";
  $params[] = password_hash($password, PASSWORD_DEFAULT);
  
  // Add roletype (always set a role)
  $validRoles = ['user', 'editor', 'admin'];
  $roletype = isset($data['roletype']) && in_array($data['roletype'], $validRoles) ? $data['roletype'] : 'user';
  $sql .= ", roletype=?";
  $types .= "s";
  $params[] = $roletype;
  
  // Add remaining required fields
  $sql .= ", fiscalcode=?, age=?, avatar=? WHERE id=?";
  $types .= "sisi";
  $params[] = $data['fiscalcode'];
  $params[] = $data['age'];
  $params[] = $data['avatar'];
  $params[] = $id;
  
  error_log('ROLO TYPE: ' . print_r($data['roletype'], true));
  
  $stm = $conn->prepare($sql);
  $stm->bind_param($types, ...$params);
  $res = $stm->execute();
  $stm->close();
  return $res;
}

function createUser(array $data) : int {
  require_once '../connection.php';
  $conn = getConnection();
  $sql = 'INSERT INTO users (username, email, password, roletype, fiscalcode, age, avatar) values (?,?,?,?,?,?,?)';
  $stm = $conn->prepare($sql);
  
  // Hash the password with a secure algorithm
  $password = password_hash($data['password'] ?? 'test123', PASSWORD_DEFAULT);
  
  // Set default roletype if not valid
  $validRoles = ['user', 'editor', 'admin'];
  $roletype = isset($data['roletype']) && in_array($data['roletype'], $validRoles) ? $data['roletype'] : 'user';
  
  $stm->bind_param('sssssis',
    $data['username'],
    $data['email'],
    $password,
    $roletype,
    $data['fiscalcode'],
    $data['age'],
    $data['avatar']
  );
  $res = $stm->execute();
  $stm->close();
  return $conn->insert_id;
}