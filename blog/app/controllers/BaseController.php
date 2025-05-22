<?php

namespace App\Controllers;
use PDO;

abstract class BaseController {

  protected $content = '';
  protected string $tplDir = __DIR__ . '/../views/';
  protected $layout = __DIR__ . '/../../layout/index.tpl.php';

  public function __construct(
    protected \PDO $conn
  ) {}

  public function display(): void {
      require $this->layout;
  }
  
  public function setContent(string $content): void {
      $this->content = $content;
  }

   public function getContent(): string {
     return  $this->content ;
  }
  
   public function setTplDir(string $dir): void {
      $this->tplDir = $dir;
  }

   public function getTplDir(): string {
     return  $this->tplDir ;
  }

    public function setLayout(string $layout): void {
      $this->tplDir = $layout;
  }

   public function getLayout(): string {
     return  $this->layout ;
  }
}
