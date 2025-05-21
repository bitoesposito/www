<?php

namespace App\Controllers;
use PDO;

class PostController {

  protected string $tplDir = __DIR__ . '/../views/';

  public function __construct(
    protected PDO $conn,
    protected string $layout = './layout/index.tpl.php',
    protected string $content = '',
  ) {}

  public function display() {
      require_once $this->layout;
  }

  public function show(int $postid) {
      $message = 'This is my first post';
      ob_start();
      require_once $this->tplDir.'post.tpl.php';
      $this->content = ob_get_contents();
      ob_end_clean();
  }

  public function showPosts() {
    $posts = $this->conn->query('SELECT * FROM posts', PDO::FETCH_ASSOC)->fetchAll();
    ob_start();
    require_once $this->tplDir.'posts.tpl.php';
    $this->content = ob_get_contents();
    ob_end_clean();
  }
}