<?php

foreach ($posts as $post) {
?>

  <article class="card mb-3">
    <div class="card-body">
      <div class="d-flex flex-start">

        <div class="w-100">
          <h3 class="mb-0"><a href="/blog/posts/<?= $post['id'] ?>"><?= htmlentities($post['title']) ?></a></h3>
          <p class="h5 mb-0"><?= htmlentities($post['message']) ?></p>
        </div>

        <div class="d-flex flex-column gap-1">
        <time style="line-height: 1; white-space: nowrap;" datetime="<?= htmlentities($post['datecreated']) ?>"><?= htmlentities($post['datecreated']) ?></time>
        <a style="line-height: 1;" href="mailto:<?= htmlentities($post['email']) ?>"><?= htmlentities($post['email']) ?></a>
        </div>

      </div>
    </div>
  </article>

<?php
}
?>