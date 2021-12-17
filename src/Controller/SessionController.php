<?php
class SessionController extends BaseController {

  public function create() {
    try {
      $bodyParams = $this->getBodyParams();
      $userModel = new UserModel();
      
      $user = $userModel->findUserByEmailWithPassword($bodyParams['email']);

      if (empty($user)) {
        $this->invalidEmailOrPassword();
      }

      if (!password_verify($bodyParams['password'], $user['password'])) {
        $this->invalidEmailOrPassword();
      }

      $hashed = password_hash($user['email'], PASSWORD_BCRYPT);

      $userModel->saveToken($user['id'], $hashed);

      $this->response(
        array(
          'id' => $user['id'],
          'email' => $user['email'],
          'first_name' => $user['first_name'],
          'last_name' => $user['last_name'],
          'created_at' => $user['created_at'],
          'updated_at' => $user['created_at'],
          'token' => $hashed
        ),
        array('Content-Type: application/json', "{$this->request->serverProtocol} 200 OK")
      );
    } catch (Error $e) {
      $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
      $strErrorHeader = "{$this->request->serverProtocol} 500 Internal Server Error";
      $this->response(
        array('error' => $strErrorDesc), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  public function delete() {
    try {
      $this->getUserAuth();
      $bodyParams = $this->getBodyParams();
      $userModel = new UserModel();

      $userModel->invalidateToken($this->user['id'], $this->user['token']);

      $this->response(
        array(),
        array('Content-Type: application/json', "{$this->request->serverProtocol} 204 No Content")
      );
    } catch (Error $e) {
      $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
      $strErrorHeader = "{$this->request->serverProtocol} 500 Internal Server Error";
      $this->response(
        array('error' => $strErrorDesc), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  private function invalidEmailOrPassword() {
    $this->response(
      array('error' => "Email/senha incorretos"), 
      array('Content-Type: application/json', "{$this->request->serverProtocol} 400 Bad Request")
    );
  }
}