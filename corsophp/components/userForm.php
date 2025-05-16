<?php

  if($user && $user['id']) {
    $action = 'update';
    $buttonName = 'Update';
    $formTitle = 'Update user';
  } else {
    $action = 'create';
    $buttonName = 'Create';
    $formTitle = 'Create new user';
  }

  foreach ($user as $value) {
    $value = htmlspecialchars($value);
  }

?>

<h3 class="mb-3"><?= $formTitle ?></h3>

<form action="controller/updateRecord.php" method="post" class="d-flex flex-column gap-3">

  <input type="hidden" name="id" value="<?= $user['id'] ?>">
  <input type="hidden" name="action" value="<?= $action ?>">
  
  <div class="container p-0 d-flex flex-column gap-2">
    <div class="d-flex flex-column">
      <label id="username" name="username" class="form-label mb-0">Username</label>
      <input type="text" name="username" id="username" class="form-control" placeholder="Insert username..." value="<?= $user['username'] ?>">
    </div>

    <div class="d-flex flex-column">
      <label id="email" name="email" class="form-label mb-0">Email</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Insert email..." value="<?= $user['email'] ?>">
    </div>

    <div class="d-flex flex-column">
      <label id="fiscalcode" name="fiscalcode" class="form-label mb-0">Fiscal code</label>
      <input type="text" name="fiscalcode" id="fiscalcode" class="form-control" placeholder="Insert fiscal code..." value="<?= $user['fiscalcode'] ?>">
    </div>

    <div class="d-flex flex-column">
      <label id="age" name="age" class="form-label mb-0">Age</label>
      <input type="number" name="age" id="age" class="form-control" placeholder="Insert age..." value="<?= $user['age'] ?>">
    </div>
  </div>

  <div id="buttons" class="d-flex w-100 justify-content-between gap-2">
  <button class="btn btn-outline-secondary" style="width: min-content; white-space: nowrap">cancel</button>

<div class="d-flex gap-2">
<div class="d-flex gap-2 w-100 justify-content-end">
  <?php if($action == 'update') { ?>
    <button class="btn btn-danger">Delete</button>
  <?php } ?>
  <button class="btn btn-primary"><?= $buttonName ?></button>
</div>
</div>
  </div>
</form>