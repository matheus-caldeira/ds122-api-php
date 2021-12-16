<?php

class BookModel extends BaseModel {
  public function getBooks($limit, $offset) {
    return $this->select(
      "SELECT id, title, description, image FROM books ORDER BY id ASC LIMIT ? OFFSET ?",
      ["ii", $limit, $offset]
    );
  }

  public function updateBook($id, $title, $description, $image) {
    $this->insert("UPDATE books SET first_name = ?, last_name = ?, image = ? WHERE id = ? LIMIT 1;",
      ["sssi", $first_name, $last_name, $image, $id]
    );
  }

  public function createBook($title, $description, $image) {
    $this->insert(
      "INSERT into books(title, description, $image) VALUES (?, ?, ?);",
      ["sss", $title, $description, $image]
    );

    return $this->connection->insert_id;
  }
}