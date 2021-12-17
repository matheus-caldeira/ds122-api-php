<?php

class BookModel extends BaseModel {
  public function getBooks($limit, $offset) {
    return $this->select(
      "SELECT id, title, description, image, created_at, updated_at FROM books ORDER BY id ASC LIMIT ? OFFSET ?",
      ["ii", $limit, $offset]
    );
  }

  public function findBook($id) {
    $book = $this->select("SELECT id, title, description, image, created_at, updated_at FROM books WHERE id = ? LIMIT 1;", ["i", $id]);

    if (empty($book))
      return NULL;

    return $book[0];
  }

  public function updateBook($id, $title, $description) {
    $this->insert("UPDATE books SET title = ?, description = ? WHERE id = ? LIMIT 1;",
      ["ssi", $title, $description, $id]
    );
  }

  public function updateBookImage($id, $image) {
    $this->insert("UPDATE books SET image = ? WHERE id = ? LIMIT 1;",
      ["si", $image, $id]
    );
  }

  public function createBook($title, $description) {
    $this->insert(
      "INSERT into books(title, description) VALUES (?, ?);",
      ["ss", $title, $description]
    );

    return $this->connection->insert_id;
  }
}