<?php

function view($view, $data = [], $viewPath = __DIR__ . '/../app/views/') {
  extract($data, EXTR_OVERWRITE);
  ob_start();
  require_once $viewPath . $view . '.tpl.php';
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

function dd(...$data): void {
  var_dump($data);
  die;
}

function redirect(string $url = '/'): void {
  header("Location:$url");
  exit();
}

function isUserLoggedin(): bool{
  return $_SESSION['loggedin'] ?? false;
}

function getUserLoggedInFullname(): string{
  return $_SESSION['userData']['username'] ?? '';
}

function getUserRole(): string{
  return $_SESSION['userData']['roletype'] ?? '';
}

function getUserEmail(): string {
  return $_SESSION['userData']['email'] ?? '';
}

function isUserAdmin(): bool{
  return getUserRole() === 'admin';
}

function userCanUpdate(): bool{
  $role = getUserRole();
  return  $role === 'admin' || $role === 'editor';
}

function userCanDelete(): bool{
  return  isUserAdmin();
}

function getUserId(): int {
  return $_SESSION['userData']['id'] ?? 0;
}

function userCanDeleteComment($commentEmail): bool{
  return isUserAdmin() || ($commentEmail === getUserEmail() && isUserLoggedin());
}