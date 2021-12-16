<?php
  require_once PROJECT_ROOT_PATH . "/Routes/Request.php";
  require_once PROJECT_ROOT_PATH . "/Routes/Router.php";

  $router = new Router(new Request);

  $router->get('/users', function($request) {
    $usersController = new UsersController($request);
    $usersController->index();
  });

  $router->post('/users', function($request) {
    $usersController = new UsersController($request);
    $usersController->create();
  });

  $router->put('/users', function($request) {
    $usersController = new UsersController($request);
    $usersController->update();
  });

  $router->get('/books', function($request) {
    $usersController = new UsersController($request);
    $usersController->index();
  });

  $router->post('/books', function($request) {
    $usersController = new UsersController($request);
    $usersController->create();
  });

  $router->put('/books', function($request) {
    $usersController = new UsersController($request);
    $usersController->update();
  });

  $router->post('/user_books', function($request) {
    $usersController = new UsersController($request);
    $usersController->create();
  });

  $router->delete('/user_books', function($request) {
    $usersController = new UsersController($request);
    $usersController->update();
  });
?>