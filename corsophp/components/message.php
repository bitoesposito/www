<div
  id="message"
  style="display: absolute !important; top: 0; right: 0; z-index: 1000;"
  class="fade-out py-2 mb-0 alert alert-<?= $alertType ?? 'success'?>">
  <p style="line-height: 1; margin: 0;"><?= $message ?? '' ?></p>
</div>