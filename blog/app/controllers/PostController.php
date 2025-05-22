<?php

namespace App\Controllers;

use App\Models\Post;
use PDO;

class PostController {

  protected string $tplDir = __DIR__ . '/../views/';

  public function __construct(
    protected PDO $conn,
    protected Post $post,
    protected string $layout = './layout/index.tpl.php',
    protected string $content = '',
  ) {}

  public function display() {
      require_once $this->layout;
  }

  public function show(int $postid) {
    $post = $this->post->findByPostId($postid);
    return view('post', compact('post'), $this->tplDir);
  }

  public function create() {
    return view('newpost', [], $this->tplDir);
  }

  public function save() {
    $post = [
      'email' => $_POST['email'] ?? '',
      'title' => $_POST['title'] ?? '',
      'message' => $_POST['message'] ?? ''
    ];
    return $this->post->save($_POST);
  }

  public function getPosts() {
    $posts = $this->post->all();
    return view('posts', ['posts' => $posts], $this->tplDir);
  }

  public function process() {
    $url = $_SERVER['REQUEST_URI'] ?? $_SERVER['REDIRECT_URL'];
    $segment = trim(parse_url($url, PHP_URL_PATH), '/');
    
    if (strpos($segment, 'blog') === 0) {
      $segment = trim(substr($segment, 4), '/');
    }
    
    if ($segment === '' || $segment === 'posts') {
      $this->content = $this->getPosts();
      return;
    } 

    $tokens = explode('/', $segment);
    $method = $_SERVER['REQUEST_METHOD'];
    $par = $tokens[1] ?? '';
    
    if($method === 'POST' && $tokens[0] === 'posts' && $par === 'save') {
      $result = $this->save();
      header('Location: /blog');
      return;
    }
    
    if ($tokens[0] === 'posts' && isset($par) && is_numeric($par) && $method === 'GET') {
      $this->content = $this->show((int)$tokens[1]);
    } else if ($tokens[0] === 'posts' && isset($tokens[1]) && $tokens[1] === 'create' && $method === 'GET') {
      $this->content = $this->create();
    } else {
      $this->content = 'Method not found';
    }
  }
}