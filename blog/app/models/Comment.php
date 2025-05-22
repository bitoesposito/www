<?php

namespace App\Models;

use PDO;

class Comment {

  public function __construct(
    protected PDO $conn,
  ) {}

  public function all($postId) {
    $result = [];
    $sql = 'SELECT * FROM postscomments WHERE post_id = :postId ORDER BY datecreated DESC';
    $stm = $this->conn->prepare($sql);
    $stm->bindParam('postId', $postId, PDO::PARAM_INT);
    if ($stm) {
      $stm->execute();
      $result = $stm->fetchAll(PDO::FETCH_OBJ);
    }
    return $result;
  }

  public function save(array $comment, int $postid): bool {
      $ret = false;

      $sql = 'INSERT INTO  postscomments ( post_id,  email, comment ,datecreated) values ';
      $sql .= ' (:postid,  :email, :comment ,NOW())';

      $stm = $this->conn->prepare($sql);

      if ($stm) {
        $res = $stm->execute([
          'postid' => $postid,
          'email' => $comment['email'],
          'comment' => $comment['comment'] 
          ]
        );

        return $stm->rowCount();
      }
        
      return $ret;
    }

  public function delete($commentId) {
    $ret = 0;

    $sql = 'DELETE FROM  postscomments  ';
    $sql .= ' where id = :commentid';

    $stm = $this->conn->prepare($sql);

    if ($stm) {
      $stm->bindParam('commentid', $commentId, PDO::PARAM_INT);
      $res = $stm->execute();
      return $stm->rowCount();
    }

    return $ret;
}
  
}
