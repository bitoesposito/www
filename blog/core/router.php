<?php
namespace App\Core;

use Exception;
use App\Controllers\PostController;

class Router {

  public function __construct(
    protected array $routes = [
      'POST' => [],
      'GET' => []
    ]
  ) {}
  
  public function dispatch() {
    $url = $_SERVER['REQUEST_URI'] ?? $_SERVER['REDIRECT_URL'];
    $segment = trim(parse_url($url, PHP_URL_PATH), '/');
    $segment = $segment ?: '/';

    $method = $_SERVER['REQUEST_METHOD'];
    $urls = $this->routes[$method];
    
    // Exact match
    if (array_key_exists($segment, $urls)) {
      return $urls[$segment];
    }
    
    return $this->matchRoute($urls, $segment);
  }

  protected function matchRoute($urls, $segment) {
    foreach ($urls as $route => $classArray) {
      // Convert route format with {param} to regex pattern
      $pattern = preg_replace('/{([a-zA-Z0-9_-]+)}/', '([a-zA-Z0-9_-]+)', $route);
      
      // Add delimiters for preg_match
      $pattern = '#^' . $pattern . '$#';
      $matches = [];
      
      if (preg_match($pattern, $segment, $matches)) {
        // Extract parameters - first entry is the full match
        array_shift($matches);
        
        // Get the handler and add the parameters
        $result = $classArray;
        
        // Add parameters from URL if they exist
        foreach ($matches as $value) {
          if (is_numeric($value)) {
            $value = (int)$value;
          }
          $result[] = $value;
        }
        
        return $result;
      }
    }
    
    throw new Exception('Route not found: ' . $segment);
  }

  public function loadRoutes(array $routes) {
    $this->routes = $routes;
  }

  public function getRoutes() {
    return $this->routes;
  }
}