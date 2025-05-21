<?php

return [
  'driver' => 'mysql',
  'host' => 'localhost',
  'database' => 'blog',
  'username' => 'root',
  'password' => '',
  'charset' => 'utf8',
  // 'dsn' => 'mysql:host=localhost;dbname=blog;charset=utf8',
  'options' => [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]  
];