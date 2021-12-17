<?php

class UserModel extends BaseModel {
  public function getUsers($limit, $offset) {
    return $this->select(
      "SELECT id, email, first_name, last_name, created_at, updated_at FROM users ORDER BY id ASC LIMIT ? OFFSET ?",
      ["ii", $limit, $offset]
    );
  }

  public function findUserByEmail($email) {
    $user = $this->select("SELECT id, email, first_name, last_name, created_at, updated_at FROM users WHERE email = ? LIMIT 1;", ["s", $email]);

    if (empty($user))
      return NULL;

    return $user[0];
  }

  public function findUserByToken($token) {
    $user = $this->select(
      "SELECT id, token, first_name, last_name, created_at, updated_at FROM users WHERE token = ? LIMIT 1;",
      ["s", $token]
    );

    if (empty($user))
      return NULL;

    return $user[0];
  }

  public function findUserByEmailWithPassword($email) {
    $user = $this->select("SELECT id, email, password, first_name, last_name, created_at, updated_at FROM users WHERE email = ? LIMIT 1;", ["s", $email]);

    if (empty($user))
      return NULL;

    return $user[0];
  }

  public function invalidateToken($id, $token) {
    $this->insert("UPDATE users SET token = NULL WHERE id = ? AND token = ? LIMIT 1;",
      ["is", $id, $token]
    );
  }

  public function saveToken($id, $token) {
    $this->insert("UPDATE users SET token = ? WHERE id = ? LIMIT 1;",
      ["si", $token, $id]
    );
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