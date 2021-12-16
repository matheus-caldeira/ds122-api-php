<?php

class UserModel extends BaseModel {
  public function getUsers($limit, $offset) {
    return $this->select(
      "SELECT id, email, first_name, last_name FROM users ORDER BY id ASC LIMIT ? OFFSET ?",
      ["ii", $limit, $offset]
    );
  }

  public function findUserByEmail($email) {
    $user = $this->select("SELECT id, email, first_name, last_name FROM users WHERE email = ? LIMIT 1;", ["s", $email]);

    if (empty($user))
      return NULL;

    return $user[0];
  }

  public function findUserByEmailWithPassword($email) {
    $user = $this->select("SELECT id, email, password FROM users WHERE email = ? LIMIT 1;", ["s", $email]);

    if (empty($user))
      return NULL;

    return $user[0];
  }

  public function updateUser($id, $first_name, $last_name) {
    $this->insert("UPDATE users SET first_name = ?, last_name = ? WHERE id = ? LIMIT 1;",
      ["ssi", $first_name, $last_name, $id]
    );
  }

  public function createUser($email, $first_name, $last_name, $password) {
    $this->insert(
      "INSERT into users(email, first_name, last_name, password) VALUES (?, ?, ?, ?);",
      ["ssss", $email, $first_name, $last_name, $password]
    );

    return $this->connection->insert_id;
  }
}