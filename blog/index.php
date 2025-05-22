<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use App\Controllers\BaseController;
use App\Controllers\PostController;
use App\Database\dbFactory;
use App\Core\Router;
use App\Models\Post;

require_once './core/bootstrap.php';

require_once './database/dbFactory.php';
$data = require_once './config/database.php';
$appConfig = require './config/app.config.php';

$router = new Router($appConfig['routes']);

try {
  $arrController = $router->dispatch();
  
  if (!is_array($arrController) || count($arrController) < 2) {
    throw new Exception('Invalid route configuration');
  }
  
  $conn = (dbFactory::create($data))->getConn();

  $controllerClass = $arrController[0];
  $method = $arrController[1];
  
  // Create controller
  $controller = new $controllerClass($conn);
  
  if(method_exists($controller, $method)) {
    // Check if we have parameters for the method
    if (isset($arrController[2])) {
      // Get all parameters (keys 2 and beyond)
      $params = array_slice($arrController, 2);
      // Call the method with all parameters using call_user_func_array
      call_user_func_array([$controller, $method], $params);
    } else {
      $controller->$method();
    }
  } else {
    throw new Exception("Method '$method' not found in controller");
  }

  if ($controller instanceof BaseController) {
    $controller->display();
  }
} catch (Exception $e) {
  die($e->getMessage());
}

require_once './layout/footer.php';