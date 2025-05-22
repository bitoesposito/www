<?php

use App\Controllers\PostController;

return [
  'routes' => [
    'GET' => [
      'blog' => [PostController::class, 'getPosts'],
      'blog/posts' => [PostController::class, 'getPosts'],
      'blog/create' => [PostController::class, 'create'],
      'blog/posts/{id}' => [PostController::class, 'show'],
    ],
    'POST' => [
      'blog/posts/save' => [PostController::class, 'save'],
      'blog/posts/create' => [PostController::class, 'create']
    ]
  ]
];