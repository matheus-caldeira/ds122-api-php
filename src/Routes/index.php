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

  $router->post('/session', function($request) {
    $sessionController = new SessionController($request);
    $sessionController->create();
  });

  $router->delete('/session', function($request) {
    $sessionController = new SessionController($request);
    $sessionController->delete();
  });

  $router->get('/books', function($request) {
    $booksController = new BooksController($request);
    $booksController->index();
  });

  $router->post('/books', function($request) {
    $booksController = new BooksController($request);
    $booksController->create();
  });

  $router->put('/books', function($request) {
    $booksController = new BooksController($request);
    $booksController->update();
  });

  $router->get('/user_books', function($request) {
    $userBooksController = new UserBooksController($request);
    $userBooksController->index();
  });

  $router->post('/user_books', function($request) {
    $userBooksController = new UserBooksController($request);
    $userBooksController->create();
  });

  $router->delete('/user_books', function($request) {
    $userBooksController = new UserBooksController($request);
    $userBooksController->delete();
  });
?>