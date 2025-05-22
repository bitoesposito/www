<?php

use App\Controllers\PostController;
use App\Controllers\LoginController;

return [
  'routes' => [
    'GET' => [
      'blog' => [PostController::class, 'getPosts'],
      'blog/posts' => [PostController::class, 'getPosts'],
      'blog/create' => [PostController::class, 'create'],
      'blog/posts/{id}' => [PostController::class, 'show'],
      'blog/posts/{id}/edit' => [PostController::class, 'editForm'],
      'blog/posts/{id}/comments/{commentId}/delete' => [PostController::class, 'deleteComment'],
      'blog/auth/login' => [LoginController::class, 'showLogin'],
      'blog/auth/signup' => [LoginController::class, 'showSignup'],
    ],
    'POST' => [
      'blog/posts' => [PostController::class, 'save'],
      'blog/posts/{id}/delete' => [PostController::class, 'delete'],
      'blog/posts/{id}/edit' => [PostController::class, 'edit'],
      'blog/posts/{id}/comments' => [PostController::class, 'saveComment'],
      'blog/auth/login' => [LoginController::class, 'login'],
      'blog/auth/signup' => [LoginController::class, 'signup'],
      'blog/auth/logout' => [LoginController::class, 'logout'],
    ]
  ]
];