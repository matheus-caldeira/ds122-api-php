<?php

  define("PROJECT_ROOT_PATH", __DIR__ . "/src");
  require PROJECT_ROOT_PATH . "/inc/bootstrap.php";

  $conn = mysqli_connect(
    DB_HOST,
    DB_USERNAME,
    DB_PASSWORD,
    DB_DATABASE_NAME
  );

  if (!$conn) {
    die(mysqli_error());
  }

  echo "Connection Ok";
  
  $sql = "CREATE table users (
    id         int auto_increment,
    email      varchar(30) not null,
    first_name varchar(30) null,
    last_name  varchar(30) null,
    password varchar(120) not null,
    token varchar(120) null,
    created_at timestamp default CURRENT_TIMESTAMP,
    updated_at timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    constraint users_pk
        primary key (id)
  );";

  $conn->query($sql);

  $sql = "CREATE table books (
    id          int auto_increment,
    title       varchar(50) not null,
    description text        null,
    image varchar(120)      null,
    created_at timestamp default CURRENT_TIMESTAMP,
    updated_at timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    constraint books_pk
        primary key (id)
  );";

  $conn->query($sql);

  $sql = "CREATE table user_books (
    id      int auto_increment,
    user_id int null,
    book_id int null,
    created_at timestamp default CURRENT_TIMESTAMP,
    updated_at timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    constraint user_books_pk
        primary key (id),
    constraint user_books_books_id_fk
        foreign key (book_id) references books (id),
    constraint user_books_users_id_fk
        foreign key (user_id) references users (id)
  );";

  $conn->query($sql);

  echo "\n Tabelas criadas com sucesso";
    
  $conn->close();
?>