<?php
use App\Controllers\PostController;
use App\Database\dbFactory;

require_once './app/controllers/PostController.php';
require_once './layout/head.php';
require_once './layout/nav.php';

require_once './database/dbFactory.php';
$data = require_once './config/database.php';

try {
  $conn = (dbFactory::create($data))->getConn();
  $controller = new PostController($conn);
  $controller->showPosts();
  $controller->display();
} catch (Exception $e) {
  die($e->getMessage());
}

require_once './layout/footer.php';