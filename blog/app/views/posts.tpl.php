<?php

foreach ($posts as $post) {
  ?>

  <article>
    <h3><?= htmlentities($post['title']) ?></h3>
    <p><?= htmlentities($post['message']) ?></p>
  </article>

  <?php
}
?>