<?php
class BaseController {
  private $supportedBodyMethods = array(
    "POST",
    "PUT",
    "PATCH"
  );

  function __construct($request) {
    $this->request = $request;
  }

  protected function getBodyParams() {
    if(in_array($this->request->requestMethod, $this->supportedBodyMethods)) {
      $this->checkContentType();
      $body = file_get_contents("php://input");

      $object = json_decode($body, true);
      
      if (!is_array($object)) {
        $this->invalidJsonFormat();
      }

      return $object;
    }
  }

  protected function getQueryParams() {
    parse_str($this->request->queryString, $result);

    return $result;
  }

  protected function getUserAuth() {
    $email = NULL;
    $password = NULL;
    $mod = NULL;
    if (isset($_SERVER['PHP_AUTH_USER'])) {
      $email = $_SERVER['PHP_AUTH_USER'];
      $password = $_SERVER['PHP_AUTH_PW'];
      $mod = 'PHP_AUTH_USER';
    } elseif (isset( $_SERVER['HTTP_AUTHORIZATION'])) {
        if (preg_match( '/^basic/i', $_SERVER['HTTP_AUTHORIZATION']))
          list( $email, $password ) = explode( ':', base64_decode( substr( $_SERVER['HTTP_AUTHORIZATION'], 6 ) ) );
      $mod = 'HTTP_AUTHORIZATION';
    }
  
    if (is_null($email))
      $this->unauthorizedUser();

    $userModel = new UserModel();
    $user = $userModel->findUserByEmailWithPassword($email);

    if (empty($user))
      $this->unauthorizedUser();

    if (password_verify($password, $user['password'])) {
      $this->user = $user;
    } else {
      $this->unauthorizedUser();
    }
  }

  private function unauthorizedUser() {
    $this->response(
      array('error' => "Unauthorized"),
      array("{$this->request->serverProtocol} 401 Unauthorized")
    );
  }

  private function checkContentType() {
    $content_type = isset($this->request->contentType) ? $this->request->contentType : '';
    if (stripos($content_type, 'application/json') === false) {
      $this->response(
        array('error' => "Content-Type must be application/json"),
        array('Content-Type: application/json', "{$this->request->serverProtocol} 415 Unsupported Media Type")
      );
    }
  }

  private function invalidJsonFormat() {
    $this->response(
      array('error' => "Failed to decode JSON object"),
      array('Content-Type: application/json', "{$this->request->serverProtocol} 400 Bad Request")
    );
  }

  protected function response($data, $httpHeaders=array()) {
    header_remove('Set-Cookie');

    if (is_array($httpHeaders) && count($httpHeaders)) {
      foreach ($httpHeaders as $httpHeader) {
        header($httpHeader);
      }
    }

    echo json_encode($data);
    exit;
  }

}