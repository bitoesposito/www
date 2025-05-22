<?php

namespace App\Controllers;
use App\Models\Post;
use PDO;
use App\Controllers\BaseController;

class PostController extends BaseController {

  protected string $tplDir = __DIR__ . '/../views/';
  protected Post $post;
  protected $content = '';
  protected $layout = __DIR__ . '/../../layout/index.tpl.php';

  public function __construct(
    protected PDO $conn
  ) {
    $this->post = new Post($conn);
  }

  public function show(int $postid) {
    $post = $this->post->findByPostId($postid);
    $this->content = view('post', compact('post'));
  }

  public function create() {
    $this->content = view('newpost');
  }

  public function save() {

    
    $post = [
      'email' => $_POST['email'] ?? '',
      'title' => $_POST['title'] ?? '',
      'message' => $_POST['message'] ?? ''
    ];

    return $this->post->save($_POST);

    header('Location: /blog');
  }

  public function getPosts() {
    $posts = $this->post->all();
    $this->content = view('posts', ['posts' => $posts], $this->tplDir);
  }

  public function display() {
    require_once $this->layout;
  }
}