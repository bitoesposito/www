<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use App\Controllers\PostController;
use App\Database\dbFactory;

require_once './app/controllers/PostController.php';
require_once './layout/head.php';
require_once './layout/nav.php';
require_once './app/models/Post.php';
require_once './helpers/functions.php';

require_once './database/dbFactory.php';
$data = require_once './config/database.php';

try {
  $conn = (dbFactory::create($data))->getConn();
  $post = new \App\Models\Post($conn);
  $controller = new PostController($conn, $post);
  $controller->process();
  $controller->display();
} catch (Exception $e) {
  die($e->getMessage());
}

require_once './layout/footer.php';