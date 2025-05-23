<?php

namespace App\Controllers;
use App\Models\Post;
use App\Models\Comment;
use PDO;
use App\Controllers\BaseController;

class PostController extends BaseController {

  protected Post $post;

  public function __construct(
    protected \PDO $conn
  ) {
    parent::__construct($conn);
    $this->post = new Post($conn);
  }

  public function getPosts() {
    $posts = $this->post->all();
    $this->content = view('posts', ['posts' => $posts], $this->tplDir);
  }
  
  public function show(int $postid) {
    $post = $this->post->findByPostId($postid);
    $comment = new Comment($this->conn);
    $comments = $comment->all($postid);
    $this->content = view('post', compact('post', 'comments'));
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

  public function create() {
    $this->content = view('newpost');
  }

  public function save() {
    $post = [
      'user_id' => $_SESSION['userData']['id'] ?? '',
      'email' => $_POST['email'] ?? '',
      'title' => $_POST['title'] ?? '',
      'message' => $_POST['message'] ?? ''
    ];

    $this->post->save($post);

    // Redirect to a GET page after POST processing
    header('Location: /blog');
    exit;
  }

  public function saveComment(int $postid ): void {
    $comment = [
      'email' => isUserLoggedin() ? getUserEmail() : ($_POST['email'] ?? ''),
      'comment' => $_POST['comment'] ?? '',
      'user_id' => isUserLoggedin() ? getUserId() : null
    ];
    
    $commentObj = new Comment($this->conn);
    $commentObj->save($comment, $postid);
    
    header('Location: /blog/posts/'.$postid);
    exit;
  }

  public function delete($postId) {
    $this->post->delete($postId);
    header('Location: /blog');
    exit;
  }

  public function display(): void {
    require $this->layout;
  }

  public function editForm(int $postid) {
    $post = $this->post->findByPostId($postid);
    $this->content = view('editpost', compact('post'));
  }

  public function deleteComment(int $id, int $commentId) {
    $comment = new Comment($this->conn);
    $comment->delete($commentId);
    
    header('Location: /blog/posts/'.$id);
    exit;
  }
}