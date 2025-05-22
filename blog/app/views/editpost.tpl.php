<h1>Edit post</h1>

<form action="/blog/posts/<?=$post['id']?>/edit" method="POST">
  <div class="mb-3">
    <label for="email" class="form-label mb-0">Email address</label>
    <input required type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?=$post['email']?>">
  </div>
  <div class="mb-3">
    <label for="title" class="form-label mb-0">Title</label>
    <input required type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="<?=$post['title']?>">
  </div>
  <div class="mb-3">
    <label for="message" class="form-label mb-0">Content</label>
    <textarea required class="form-control" id="message" name="message" placeholder="Enter message"><?=$post['message']?></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Save</button>
</form>