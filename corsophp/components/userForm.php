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

  // Sanitize user data, handling null values
  foreach ($user as $key => $value) {
    $user[$key] = $value !== null ? htmlspecialchars((string)$value) : '';
  }

?>

<h3 class="mb-3"><?= $formTitle ?></h3>

<form enctype="multipart/form-data" action="controller/updateRecord.php" method="post" class="d-flex flex-column gap-3">

  <input type="hidden" name="id" value="<?= $user['id'] ?>">
  <input type="hidden" name="action" value="<?= $action ?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?= convertMaxUploadSizeToBytes() ?>">
  <input type="hidden" name="oldAvatar" value="<?= $user['avatar'] ?>">

  <div class="container p-0 d-flex flex-column gap-2">
    <div class="d-flex flex-column">
      <label id="username" name="username" class="form-label mb-0">Username</label>
      <input type="text" name="username" id="username" class="form-control" placeholder="Enter username..." value="<?= $user['username'] ?>">
    </div>

    <div class="d-flex flex-column">
      <label id="email" name="email" class="form-label mb-0">Email</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter email..." value="<?= $user['email'] ?>">
    </div>

    <div class="d-flex gap-2">
      <div class="d-flex flex-column w-100">
        <label id="password" name="password" class="form-label mb-0">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter password..." value="">
      </div>

      <div class="d-flex flex-column w-25">
        <label id="roletype" name="roletype" class="form-label mb-0">Role</label>
        <select class="form-select" name="roletype" id="roletype">
          <?php 
          foreach (getConfig('roletypes', []) as $role):
            $sel = $user['roletype'] == $role ? 'selected' : '';
            echo "\n<option $sel value='$role'>$role</option>";
          endforeach;
            ?>
        </select>
      </div>
    </div>

    <div class="d-flex flex-column">
      <label id="fiscalcode" name="fiscalcode" class="form-label mb-0">Fiscal code</label>
      <input type="text" name="fiscalcode" id="fiscalcode" class="form-control" placeholder="Enter fiscal code..." value="<?= $user['fiscalcode'] ?>">
    </div>

    <div class="d-flex flex-column">
      <label id="age" name="age" class="form-label mb-0">Age</label>
      <input type="number" name="age" id="age" class="form-control" placeholder="Enter age..." value="<?= $user['age'] ?>">
    </div>

    <div class="d-flex">
    <?php
        $fileData = getImgThumbNail($user['avatar'], 'm');
        $avatar = $fileData['avatar'];
        ?>
        <img id="preview" src="<?= $fileData['avatar'] ? htmlspecialchars($fileData['avatar']) : '' ?>" class="mt-2"
            style="width:<?= $fileData['width'] ?>px;<?= $fileData['avatar'] ? '' : 'display:none' ?>; max-height: 160px; object-fit: contain; border-radius: .25rem">
    </div>

    <div class="d-flex flex-column w-100">
      <label id="avatar" name="avatar" class="form-label mb-0">Avatar</label>
      <input type="file" accept="<?= implode(',', getConfig('mimeTypes')) ?>" id="avatar" class="form-control" name="avatar" onchange="handleFileSelect(event)">
      <small class="mt-2">Image types: <?= implode(',', getConfig('mimeTypes')) ?>,<br>Max file size: <?= formatBytes(getConfig('maxFileSize')) ?></small>
    </div>


    <div id="buttons" class="d-flex w-100 justify-content-between gap-2 mb-3">
      <a href="<?= $indexPage ?>" class="btn btn-outline-secondary" style="width: min-content; white-space: nowrap">cancel</a>
      <div class="d-flex gap-2">
        <div class="d-flex gap-2 w-100 justify-content-end">
          <?php if($action == 'update') { ?>
            <a href="controller/updateRecord.php?action=delete&id=<?= $user['id'] ?>" 
               onclick="return confirm('Are you sure you want to delete this user?')"
               class="btn btn-danger">Delete</a>
          <?php } ?>
          <button type="submit" class="btn btn-primary"><?= $buttonName ?></button>
        </div>
      </div>
    </div>
  </div>


<script>
function handleFileSelect(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    const avatar = '<?= $avatar ?>';
    
    if (file && preview) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        // Se non c'è file selezionato, mostra l'avatar precedente
        preview.src = avatar;
        if (!avatar) {
            preview.style.display = 'none';
        } else {
            preview.style.display = 'block';
        }
    }
}
</script>
</form>