<?php

foreach ($posts as $post) {
  ?>

  <article>
    <h2><?= htmlentities($post['title']) ?></h2>
    <p><?= htmlentities($post['message']) ?></p>
  </article>

  <?php
}
?>