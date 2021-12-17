<?php

class UserBookModel extends BaseModel {
  public function getUserBooks($userId, $limit, $offset) {
    return $this->select(
      "SELECT ub.id, ub.book_id, b.title, b.description, b.image, b.created_at, b.updated_at from books as b JOIN user_books as ub on b.id = ub.book_id where user_id = ? ORDER BY ub.id ASC LIMIT ? offset ?;",
      ["iii", $userId, $limit, $offset]
    );
  }

  public function findUserBook($id) {
    $userBook = $this->select(
      "SELECT id, user_id, book_id, created_at, updated_at FROM user_books WHERE id = ? LIMIT 1;",
      ["i", $id]
    );

    if (empty($userBook))
      return NULL;

    return $userBook[0];
  }

  public function findUserBookByBook($userId, $bookId) {
    $userBook = $this->select(
      "SELECT id, user_id, book_id, created_at, updated_at FROM user_books WHERE user_id = ? AND book_id = ? LIMIT 1;",
      ["ii", $userId, $bookId]
    );

    if (empty($userBook))
      return NULL;

    return $userBook[0];
  }

  public function deleteUserBook($id, $userId) {
    $this->insert(
      "DELETE FROM user_books WHERE id = ? AND user_id = ?;",
      ["ii", $id, $userId]
    );
  }

  public function createUserBook($userId, $bookId) {
    $this->insert(
      "INSERT into user_books(user_id, book_id) VALUES (?, ?);",
      ["ii", $userId, $bookId]
    );

    return $this->connection->insert_id;
  }
}