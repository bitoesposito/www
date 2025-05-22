<?php

namespace App\Controllers;
use App\Models\Post;
use App\Models\Comment;
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
    $comment = new Comment($this->conn);
    $comments = $comment->all($postid);
    $this->content = view('post', compact('post', 'comments'));
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

    $this->post->save($_POST);

    header('Location: /blog');
    exit;
  }

  public function saveComment(int $postid ): void {
    $comment = [
      'email' => $_POST['email'] ?? '',
      'comment' => $_POST['comment'] ?? '',
    ];
    
    $commentObj = new Comment($this->conn);
    $commentObj->save($comment, $postid);
   
    header('Location: /blog/posts/'.$postid);
}

  public function edit(int $postid) {
    // Update post with data from POST request
    $postData = [
      'id' => $postid,
      'email' => $_POST['email'] ?? '',
      'title' => $_POST['title'] ?? '',
      'message' => $_POST['message'] ?? ''
    ];
    
    $this->post->update($postData);
    
    // Redirect to post list after update
    header('Location: /blog');
    exit;
  }

  public function delete($postId) {
    $this->post->delete($postId);
    header('Location: /blog');
    exit;
  }

  public function editForm(int $postid) {
    $post = $this->post->findByPostId($postid);
    $this->content = view('editpost', compact('post'));
  }

  public function getPosts() {
    $posts = $this->post->all();
    $this->content = view('posts', ['posts' => $posts], $this->tplDir);
  }

  public function deleteComment(int $id, int $commentId) {
    $comment = new Comment($this->conn);
    $comment->delete($commentId);
    
    header('Location: /blog/posts/'.$id);
    exit;
  }

  public function display() {
    require_once $this->layout;
  }
}