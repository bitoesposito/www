<?php

/**
 * Utility Functions for User Data Generation
 */

/**
 * Establishes database connection
 * @return mysqli Database connection object
 */
require 'connection.php';

/**
 * Generates a random name by combining random first and last names
 * @return string Random full name
 */
function getRandName()
{
  $names = ['VITO', 'SAMANTHA', 'DARIO', 'EMANUELE'];
  $lastnames = ['ESPOSITO', 'STANCO', 'FALEO', 'NATALE'];

  $rand1 = mt_rand(0, count($names) - 1);
  $rand2 = mt_rand(0, count($lastnames) - 1);

  return $names[$rand1] . ' ' . $lastnames[$rand2];
}

/**
 * Generates a random email based on a given name
 * @param string $name The name to use in email generation
 * @return string Random email address
 */
function getRandEmail($name)
{
  $domains = ['google.com', 'libero.it', 'virgilio.it', 'italia.online'];
  $rand3 = mt_rand(0, count($domains) - 1);
  return strtolower(str_replace(' ', '.', $name) . mt_rand(10, 99) . '@' . $domains[$rand3]);
}

/**
 * Generates a random 16-character fiscal code
 * @return string Random fiscal code
 */
function getRandFiscalCode()
{
  $i = 16;
  $res = '';
  while ($i > 0) {
    $res .= chr(mt_rand(65, 90));
    $i--;
  }
  return $res;
}

/**
 * Generates a random age between 0 and 120
 * @return int Random age
 */
function getRandAge()
{
  return mt_rand(0, 120);
}

/**
 * Inserts random users into the database
 * @param int $total Number of users to insert
 * @param mysqli $conn Database connection
 */
function insertRandUser(int $total, mysqli $conn)
{
  while ($total > 0) {
    $username = getRandName();
    $email = getRandEmail($username);
    $fiscalcode = getRandFiscalCode();
    $age = getRandAge();

    $sql = 'INSERT INTO users (username, email, fiscalcode, age) VALUES ';
    $sql .= "('$username', '$email', '$fiscalcode', $age)";

    $res = $conn->query($sql);
    if (!$res) {
      echo $conn->error;
    } else {
      $total--;
    }
  }
}

/**
 * Database and User Management Functions
 */

/**
 * Retrieves users from database with pagination and search
 * @param array $params Search and pagination parameters
 * @return array List of users
 */
function getUsers(array $params = [])
{
  $conn = getConnection();

  $orderBy = $params['orderBy'] ?? 'id';
  $orderDir = $params['orderDir'] ?? 'ASC';
  $search = $params['search'] ?? '';
  $limit = (int)($params['recordsPerPage'] ?? 10);
  $page = $params['page'] ?? 1;

  $start = $limit * ($page - 1);

  $records = [];

  $sql = "SELECT * FROM users";
  if ($search) {
    $sql .= " WHERE";
    if (is_numeric($search)) {
      $sql .= " (id = $search OR age = $search) ";
    } else {
      $sql .= " (fiscalcode LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%' OR roletype LIKE '%$search%')";
    }
  }
  $sql .= " ORDER BY $orderBy $orderDir LIMIT $start, $limit";
  $res = $conn->query($sql);

  if ($res) {
    while ($row = $res->fetch_assoc()) {
      $records[] = $row;
    }
  }

  return $records;
}

/**
 * Gets total count of users matching search criteria
 * @param string $search Search term
 * @return int Total number of users
 */
function getTotaUsersCount(string $search = ''): int
{
  $conn = getConnection();

  $sql = "SELECT COUNT(*) as total FROM users";
  if ($search) {
    $sql .= " WHERE";
    if (is_numeric($search)) {
      $sql .= " id = $search OR age = $search";
    } else {
      $search = $conn->real_escape_string($search);
      $sql .= " fiscalcode LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%' OR roletype LIKE '%$search%'";
    }
  }

  $res = $conn->query($sql);

  if ($res && $row = $res->fetch_assoc()) {
    return (int) $row['total'];
  }

  return 0;
}

/**
 * Validates user input data
 * @param array $data User data to validate
 * @return array Array of validation errors
 */
function validateUserData(array $data): array
{
  $errors = [];

  if (empty($data['username']) || strlen($data['username']) > 64 || strlen($data['username']) < 3) {
    $errors['username'] = 'Username must be between 3 and 64 characters';
  }

  if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email address';
  }

  if (empty($data['fiscalcode']) || strlen($data['fiscalcode']) !== 16) {
    $errors['fiscalcode'] = 'Fiscal code must be 16 characters long';
  }

  if (!is_numeric($data['age']) || $data['age'] < 18 || $data['age'] > 120) {
    $errors['age'] = 'Age must be a number between 18 and 120';
  }

  return $errors;
}

/**
 * File Upload and Management Functions
 */

/**
 * Handles avatar file upload and generates all required image versions
 * @param array $file Uploaded file data
 * @param int|null $userId User ID for file naming
 * @param string|null $oldAvatarPath Path to old avatar to delete
 * @return string|null Path to uploaded file or null if upload failed
 */
function handleAvatarUpload(array $file, int $userId = null, ?string $oldAvatarPath = null): ?string
{
  try {
    $config = require 'config.php';
    $uploadDir = $config['uploadDir'] ?? 'avatar';
    $uploadDirPath = realpath(__DIR__) . '/' . $uploadDir . '/';

    // Delete old avatar and its versions if they exist
    if ($oldAvatarPath) {
      deleteUserImages($oldAvatarPath);
    }

    // Validate file
    $fileErrors = validateFileUpload($file);
    if (!empty($fileErrors)) {
      throw new Exception(implode(', ', $fileErrors));
    }

    // Generate unique filename
    $mimeMap = [
      'image/jpeg' => 'jpg',
      'image/png' => 'png',
      'image/gif' => 'gif'
    ];

    $fileinfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileinfo->file($file['tmp_name']);
    if (!isset($mimeMap[$mimeType])) {
      throw new Exception('Invalid file type');
    }

    $extension = $mimeMap[$mimeType];
    $fileName = ($userId ? $userId . '_' : '') . bin2hex(random_bytes(8)) . '.' . $extension;
    $targetPath = $uploadDirPath . $fileName;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
      throw new Exception('Failed to move uploaded file');
    }

    // Generate thumbnail and intermediate versions
    createThumbnailAndIntermediate($uploadDir . '/' . $fileName);

    return $uploadDir . '/' . $fileName;
  } catch (Exception $e) {
    error_log('Avatar upload error: ' . $e->getMessage());
    return null;
  }
}

/**
 * Gets the upload directory path
 * @return string Upload directory path
 */
function getUploadDir()
{
  $uploadDir = getConfig('uploadDir') ?? 'avatar';
  $uploadDir = realpath(__DIR__) . '/' . trim($uploadDir, '/') . '/';
  return $uploadDir;
}

/**
 * Creates thumbnail and intermediate versions of an image
 * @param string $originalPath Path to the uploaded avatar file
 * @return bool True if successful, false otherwise
 */
function createThumbnailAndIntermediate(string $originalPath): bool
{
  try {
    $config = require 'config.php';
    $thumbnailWidth = $config['thumbnailWidth'] ?? 120;
    $intermediateWidth = $config['intermediateWidth'] ?? 600;

    // Get absolute path to the original file
    $sourcePath = realpath(__DIR__ . '/' . $originalPath);
    if (!$sourcePath || !file_exists($sourcePath)) {
      throw new Exception("Source file not found: $originalPath");
    }

    // Get mime type
    $fileinfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileinfo->file($sourcePath);
    if (!$mimeType) {
      throw new Exception("Not a valid extension of file: $sourcePath");
    }

    $fileName = basename($originalPath);
    $targetDir = getUploadDir();
    $thumbnailPath = $targetDir . 'thumbnail_' . $fileName;
    $intermediatePath = $targetDir . 'intermediate_' . $fileName;

    // Create thumbnail and intermediate versions
    resizeImage($sourcePath, $thumbnailPath, $thumbnailWidth, $mimeType);
    resizeImage($sourcePath, $intermediatePath, $intermediateWidth, $mimeType);

    return true;
  } catch (Exception $e) {
    error_log('Image processing error: ' . $e->getMessage());
    return false;
  }
}

/**
 * Resizes an image to a specified width and height
 * @param string $sourcePath Source image path
 * @param string $targetPath Destination image path
 * @param int $width Desired width
 * @param string $mimeType Image mime type
 */
function resizeImage(string $sourcePath, string $targetPath, int $width, string $mimeType): void
{
  // Create source image based on mime type
  $sourceImage = null;
  switch ($mimeType) {
    case 'image/jpeg':
      $sourceImage = imagecreatefromjpeg($sourcePath);
      break;
    case 'image/png':
      $sourceImage = imagecreatefrompng($sourcePath);
      break;
    case 'image/gif':
      $sourceImage = imagecreatefromgif($sourcePath);
      break;
    default:
      throw new Exception("Unsupported image type: $mimeType");
  }

  if (!$sourceImage) {
    throw new Exception("Failed to create image from source: $sourcePath");
  }

  $originalWidth = imagesx($sourceImage);
  $originalHeight = imagesy($sourceImage);
  $newHeight = floor($originalHeight * ($width / $originalWidth));
  $newImage = imagecreatetruecolor($width, $newHeight);

  // Preserve transparency for PNG images
  if ($mimeType === 'image/png') {
    imagealphablending($newImage, false);
    imagesavealpha($newImage, true);
  }

  imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $newHeight, $originalWidth, $originalHeight);

  // Save the resized image
  $quality = getConfig('imageQuality') ?? 90;
  switch ($mimeType) {
    case 'image/jpeg':
      imagejpeg($newImage, $targetPath, $quality);
      break;
    case 'image/png':
      imagepng($newImage, $targetPath, $quality);
      break;
    case 'image/gif':
      imagegif($newImage, $targetPath, $quality);
      break;
  }

  imagedestroy($newImage);
  imagedestroy($sourceImage);
}

/**
 * Gets the thumbnail URL for a given path
 * @param string $path The path to the uploaded avatar file
 * @return string|null The thumbnail URL or null if the file does not exist
 */
function getImgThumbNail(string $path, string $size = 's'): array
{
  $imgWidth = getConfig($size === 's' ? 'thumbnailWidth' : 'intermediateWidth', 120);
  $fileData = ['width' => $imgWidth, 'avatar' => ''];
  $prefix = $size === 's' ? 'thumbnail_' : 'intermediate_';
  $fileName = $prefix . basename($path);
  $thumbnail = getConfig('uploadDir', 'avatar')
    . '/' . $fileName;

  $uploadDir  = getUploadDir() . '/' . $fileName;
  if (file_exists($uploadDir)) {
    $fileData['avatar'] = $thumbnail;
    $fileData['width'] = $imgWidth;
  }

  return $fileData;
}

/**
 * Validates uploaded file
 * @param array $file File data to validate
 * @return array Array of validation errors
 */
function validateFileUpload(array $file): array
{
  $errors = [];

  if ($file['error'] !== UPLOAD_ERR_OK) {
    $errors[] = getUploadError($file['error']);
    return $errors;
  }
  $config = require 'config.php';

  $fileinfo = new finfo(FILEINFO_MIME_TYPE);
  $mimeType = $fileinfo->file($file['tmp_name']);
  if (!in_array($mimeType, $config['mimeTypes'] ?? ['image/jpeg'])) {
    $errors[] = 'Invalid file type. Allowed types: ' . implode(',', $config['mimeTypes']);
  }
  if ($file['size'] > $config['maxFileSize']) {
    $errors[] = 'File size exceeds ' . $config['maxFileSize'];
  }

  return $errors;
}

/**
 * Gets human-readable upload error message
 * @param int $errorCode PHP upload error code
 * @return string Error message
 */
function getUploadError(int $errorCode): string
{
  $error = '';

  switch ($errorCode) {
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
      $error = 'File size exceeds the allowed limit.';
      break;
    case UPLOAD_ERR_PARTIAL:
      $error = 'The file was only partially uploaded.';
      break;
    case UPLOAD_ERR_NO_FILE:
      $error = 'No file was uploaded.';
      break;
    case UPLOAD_ERR_NO_TMP_DIR:
      $error = 'Missing temporary folder.';
      break;
    case UPLOAD_ERR_CANT_WRITE:
      $error = 'Failed to write file to disk.';
      break;
    case UPLOAD_ERR_EXTENSION:
      $error = 'File upload stopped by extension.';
      break;
    default:
      $error = 'Unknown file upload error.';
      break;
  }
  return $error;
}

/**
 * Utility Functions
 */

/**
 * Gets request parameter with default value
 * @param string $param Parameter name
 * @param string $default Default value
 * @return string Parameter value
 */
function getParam($param, $default = '')
{
  return $_REQUEST[$param] ?? $default;
}

/**
 * Gets configuration value
 * @param string $param Configuration key
 * @return mixed Configuration value
 */
function getConfig($param)
{
  $config = require 'config.php';
  return $config[$param] ?? null;
}

/**
 * Debug function to dump and die
 * @param mixed $data Data to dump
 */
function dd(mixed $data = null)
{
  var_dump($data);
  die;
}

/**
 * Shows session message if exists
 */
function showSessionMsg()
{
  if (!empty($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
    $alertType = $_SESSION['messageType'];
    unset($_SESSION['messageType']);
    require_once 'view/message.php';
  }
}

/**
 * Sets flash message in session
 * @param string $message Message text
 * @param string $type Message type
 */
function setFlashMessage(string $message, string $type = 'info')
{
  $_SESSION['message'] = $message;
  $_SESSION['messageType'] = $type;
}

/**
 * Redirects with query parameters
 */
function redirectWithParams(): void
{
  $params = $_GET;
  if (isset($params['id']))
    unset($params['id']);
  if (isset($params['action'])) {
    unset($params['action']);
  }
  $queryString = http_build_query($params);
  header('Location:../index.php?' . $queryString);
  exit;
}

/**
 * Converts max upload size to bytes
 * @return int Size in bytes
 */
function convertMaxUploadSizeToBytes(): int
{
  $maxUploadSize = ini_get('upload_max_filesize');
  $number = (int) $maxUploadSize;
  $unit = strtoupper(substr($maxUploadSize, -1));
  switch ($unit) {
    case 'K':
      $number *= 1024;
      break;
    case 'M':
      $number *= (1024 ** 2);
      break;
    case 'G':
      $number *= (1024 ** 3);
      break;
    default:
      $number = (int) $maxUploadSize;
      break;
  }
  return $number;
}

/**
 * Formats bytes to human readable format
 * @param int $bytes Size in bytes
 * @return string Formatted size
 */
function formatBytes(int $bytes): string
{
  $units = ['B', 'KB', 'MB', 'GB'];
  $power = floor(log($bytes, 1024));
  $number = round($bytes / (1024 ** $power), 2);
  return $number . ' ' . $units[$power];
}

/**
 * Deletes all versions of a user's avatar
 * @param string $avatarPath Path to the avatar file
 * @return bool True if successful, false otherwise
 */
function deleteUserImages(string $avatarPath): bool
{
  try {
    if (!$avatarPath) {
      return true;
    }

    $uploadDir = getUploadDir();
    $fileName = basename($avatarPath);
    $filesToDelete = [
      $uploadDir . $fileName,
      $uploadDir . 'thumbnail_' . $fileName,
      $uploadDir . 'intermediate_' . $fileName
    ];

    foreach ($filesToDelete as $file) {
      if (file_exists($file)) {
        unlink($file);
      }
    }

    return true;
  } catch (Exception $e) {
    error_log('Image deletion error: ' . $e->getMessage());
    return false;
  }
}

/**
 * Verifies user login credentials
 * @param string $email User email
 * @param string $password User password
 * @param string $token CSRF token
 * @return array|null Array of user data or null if login fails
 */
function verifyLogin($email, $password, $token)
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $result = [
    'message' => 'User logged in successfully',
    'success' => true
  ];

  if ($token !== ($_SESSION['csrf'] ?? null)) {
    $result = [
      'message' => 'Invalid CSRF token',
      'success' => false
    ];
    return $result;
  }

  $email = filter_var($email, FILTER_VALIDATE_EMAIL);
  if (!$email) {
    $result = [
      'message' => 'Invalid credentials',
      'success' => false
    ];
    return $result;
  }

  include_once 'model/User.php';

  $resEmail = getUserByEmail($email);
  if (!$resEmail) {
    $result = [
      'message' => 'Invalid credentials',
      'success' => false
    ];
    return $result;
  }

  if (!password_verify($password, $resEmail['password'])) {
    $result = [
      'message' => 'Invalid credentials',
      'success' => false
    ];
    return $result;
  }

  $result['user'] = $resEmail;
  return $result;
}

/**
 * Checks if a user is logged in
 * @return bool True if user is logged in, false otherwise
 */
function isUserLogged()
{
  return $_SESSION['logged'] ?? false;
}

/**
 * Gets the username of the logged in user
 * @return string Username of the logged in user
 */
function getUserLoggedUsername()
{
  return $_SESSION['userData']['username'] ?? '';
}

/**
 * Gets the role of the logged in user
 * @return string Role of the logged in user
 */
function getUserLoggedRole()
{
  return $_SESSION['userData']['roletype'] ?? 'user';
}

/**
 * Checks if the logged in user is an admin
 * @return bool True if user is an admin, false otherwise
 */
function isUserAdmin()
{
  return getUserLoggedRole() === 'admin';
}

/**
 * Checks if the logged in user can update
 * @return bool True if user can update, false otherwise
 */
function userCanUpdate()
{
  $role = getUserLoggedRole();
  return ($role === 'admin' || $role === 'editor');
}

/**
 * Checks if the logged in user can delete
 * @return bool True if user can delete, false otherwise
 */
function userCanDelete()
{
  return isUserAdmin();
}
