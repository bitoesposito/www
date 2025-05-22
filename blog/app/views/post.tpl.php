<a class="btn btn-outline-secondary mb-3" href="/blog/posts"><i class="fa fa-arrow-left me-2"></i>Back to posts</a>

<article>
  <h3 class="mb-0"><?= htmlentities($post['title']) ?></h3>
  <p><time style="line-height: 1;" datetime="<?= htmlentities($post['datecreated']) ?>"><?= htmlentities($post['datecreated']) ?></time> by <a style="line-height: 1;" href="mailto:<?= htmlentities($post['email']) ?>"><?= htmlentities($post['email']) ?></a></p>
  <p><?= htmlentities($post['message']) ?></p>
</article>

<div class="d-flex gap-2">
  <form method="POST" action="/blog/posts/<?= $post['id'] ?>/delete" style="display: inline;">
    <button type="submit" class="btn btn-outline-danger"><i class="fa fa-trash me-2"></i>Delete</button>
  </form>

  <a class="btn btn-primary" href="/blog/posts/<?= $post['id'] ?>/edit"><i class="fa fa-pencil me-2"></i>Edit</a>
</div>

<hr>

<div class="card mb-3">

  <div class="card-body">
    <h5>Leave a comment</h5>
    <form action="/blog/posts/<?= $post['id'] ?>/comments" method="POST">
      <div class="d-flex flex-column flex-start mb-3">
        <div class="mb-2">
          <label for="email" class="form-label mb-0">Email address</label>
          <input required type="email" class="form-control" id="email" name="email" placeholder="Enter email">
        </div>
        <div>
          <label for="message" class="form-label mb-0">Content</label>
          <textarea required class="form-control" id="message" name="comment" placeholder="Enter message"></textarea>
        </div>
      </div>
      <div class="d-flex w-100 justify-content-end">
        <button type="submit" class="btn btn-success">Comment</button>
      </div>
    </form>
  </div>
</div>

<h5>Comments</h5>

<div class="d-flex flex-column mb-3">
  <?php
  foreach ($comments as $comment) : ?>

    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex flex-start">
          <div class="w-100">

            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex flex-column">
                <p class="text-body ms-2 h5"><?= $comment->comment ?></p>
                <span class="text-body ms-2">by <a href="mailto:<?= $comment->email ?>" class="text-muted"><?= $comment->email ?></a></span>
              </div>

              <div class="d-flex flex-column gap-2">
                <p class="mb-0"><time style="line-height: 1;" datetime="<?= htmlentities($comment->datecreated) ?>"><?= htmlentities($comment->datecreated) ?></time></p>
                <a href="/blog/posts/<?= $post['id'] ?>/comments/<?= $comment->id ?>/delete" class="btn btn-outline-secondary">Delete</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php endforeach; ?>
</div>