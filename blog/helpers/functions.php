<?php

function view($view, $data = [], $viewPath = __DIR__ . '/../views/') {
  extract($data, EXTR_OVERWRITE);
  ob_start();
  require_once $viewPath . $view . '.tpl.php';
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}