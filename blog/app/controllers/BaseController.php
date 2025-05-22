<?php

namespace App\Controllers;

abstract class BaseController {

  protected $content = '';

  public abstract function display();

  public function setContent($content) {
    $this->content = $content;
  }

  public function getContent() {
    return $this->content;
  }
}
