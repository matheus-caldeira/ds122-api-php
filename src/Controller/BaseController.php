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

  protected function requiredKeys($keys, $values) {
    foreach($keys as $key) {
      if (empty($values["$key"]))
        $this->keyIsRequired($key);
    }
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
    if(!isset($this->request->headers['Authorization'])) {
      $this->unauthorizedUser();
    }

    list($bearer, $token) = explode(" ", $this->request->headers['Authorization']);

    if(empty($token)) {
      $this->unauthorizedUser();
    }

    $userModel = new UserModel();
    $user = $userModel->findUserByToken($token);

    if (empty($user)){
      $this->unauthorizedUser();
    }

    $this->user = $user;
  }

  protected function unauthorizedUser() {
    $this->response(
      array('error' => "Unauthorized"),
      array("{$this->request->serverProtocol} 401 Unauthorized")
    );
  }

  private function keyIsRequired($key) {
    $this->response(
      array('error' => "$key is required"),
      array('Content-Type: application/json', "{$this->request->serverProtocol} 400 Bad Request")
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