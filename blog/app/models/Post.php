<?php

namespace App\Models;

use PDO;

class Post {

  public function __construct(
    protected PDO $conn,
  ) {}

  public function all() {
    $result = [];
    $stm = $this->conn->query('SELECT * FROM posts ORDER BY datecreated DESC', PDO::FETCH_ASSOC);
    if ($stm && $stm->rowCount()) {
      $result = $stm->fetchAll();
    }
    return $result;
  }

  public function findByPostId(int $postId) {
    $sql = 'SELECT * FROM posts WHERE id = :id';
    $stm = $this->conn->prepare($sql);
    if ($stm) {
      $res = $stm->execute(['id' => $postId]);
    } 
    if ($res) {
      $res = $stm->fetch(PDO::FETCH_ASSOC);
    }
    return $res;
  }

  public function save($post) {
    $res = false;
    $sql = 'INSERT INTO posts (user_id, title, message, email, datecreated) VALUES ';
    $sql .= '(:user_id, :title, :message, :email, NOW())';
    $stm = $this->conn->prepare($sql);
    if ($stm) {
      $res = $stm->execute([
        'user_id' => $post['user_id'],
        'title' => $post['title'],
        'message' => $post['message'],
        'email' => $post['email']
      ]);
      return $stm->rowCount();
    }
    return $res;
  }
  
  public function update($post) {
    $res = false;
    $sql = 'UPDATE posts SET title = :title, message = :message, email = :email WHERE id = :id';
    $stm = $this->conn->prepare($sql);
    if ($stm) {
      $res = $stm->execute([
        'id' => $post['id'],
        'title' => $post['title'],
        'message' => $post['message'],
        'email' => $post['email']
      ]);
      return $stm->rowCount();
    }
    return $res;
  }

  public function delete($postId) {
    $sql = "DELETE FROM posts WHERE id = :id";
    $stm = $this->conn->prepare($sql);
    if ($stm) {
      $res = $stm->execute(['id' => $postId]);
      return $stm->rowCount();
    }
    return 0;
  }
  
}
