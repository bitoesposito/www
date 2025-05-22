<article>
    <h3><a href="/blog/posts/<?=$post['id']?>"><?= htmlentities($post['title']) ?></a></h3>
    <div class="d-flex flex-column">
      <time style="line-height: 1;" datetime="<?= htmlentities($post['datecreated']) ?>"><?= htmlentities($post['datecreated']) ?></time>
      <a style="line-height: 1;" href="mailto:<?= htmlentities($post['email']) ?>"><?= htmlentities($post['email']) ?></a>
    </div>
    <p><?= htmlentities($post['message']) ?></p>
  </article>