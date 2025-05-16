<?php
?>

<form action="controller/updateRecord.php" method="post" class="d-flex flex-column gap-3">
  <button class="btn btn-outline-secondary" style="width: min-content; white-space: nowrap"><i class="fa fa-arrow-left"></i> back to list</button>
  
  <div class="container p-0 d-flex flex-column gap-2">
    <div class="d-flex flex-column">
      <label id="username" name="username" class="form-label mb-0">Username</label>
      <input type="text" name="username" id="username" class="form-control" placeholder="Insert username...">
    </div>

    <div class="d-flex flex-column">
      <label id="email" name="email" class="form-label mb-0">Email</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Insert email...">
    </div>

    <div class="d-flex flex-column">
      <label id="fiscalCode" name="fiscalCode" class="form-label mb-0">Fiscal code</label>
      <input type="text" name="fiscalCode" id="fiscalCode" class="form-control" placeholder="Insert fiscal code...">
    </div>

    <div class="d-flex flex-column">
      <label id="age" name="age" class="form-label mb-0">Age</label>
      <input type="number" name="age" id="age" class="form-control" placeholder="Insert age...">
    </div>
  </div>

  <div class="d-flex gap-2 w-100 justify-content-end">
    <button class="btn btn-danger">Delete</button>
    <button class="btn btn-primary">Update</button>
  </div>
</form>