<?php

session_start();


require_once './app/controllers/BaseController.php';
require_once './app/controllers/PostController.php';
require_once './app/controllers/LoginController.php';
require_once './app/models/Post.php';
require_once './app/models/Comment.php';
require_once './layout/head.php';
require_once './layout/nav.php';
require_once './helpers/functions.php';
require_once './core/router.php';
require_once './config/app.config.php';