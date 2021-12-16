<?php
class UsersController extends BaseController {
  public function index() {
    try {
      $arrQueryStringParams = $this->getQueryParams();
      $userModel = new UserModel();

      $intLimit = 10;
      if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
        $intLimit = $arrQueryStringParams['limit'];
      }

      $offset = 0;
      if (isset($arrQueryStringParams['offset']) && $arrQueryStringParams['offset']) {
        $offset = $arrQueryStringParams['offset'];
      }

      $arrUsers = $userModel->getUsers($intLimit, $offset);
      $this->response(
        $arrUsers,
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

  public function create() {
    try {
      $bodyParams = $this->getBodyParams();
      $userModel = new UserModel();

      $hashed = password_hash($bodyParams['password'], PASSWORD_BCRYPT);

      $checkUser = $userModel->findUserByEmail($bodyParams['email']);

      if (empty($checkUser)) {
        $user = $userModel->createUser(
          $bodyParams['email'],
          $bodyParams['first_name'],
          $bodyParams['last_name'],
          $hashed
        );

        $this->response(
          array(
            'id' => $user,
            'email' => $bodyParams['email'],
            'first_name' => $bodyParams['first_name'],
            'last_name' => $bodyParams['last_name']
          ),
          array('Content-Type: application/json', "{$this->request->serverProtocol} 200 OK")
        );
      } else {
        $this->response(
          array('error' => "Email já cadastrado"), 
          array('Content-Type: application/json', "{$this->request->serverProtocol} 400 Bad Request")
        );
      }
    } catch (Error $e) {
      $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
      $strErrorHeader = "{$this->request->serverProtocol} 500 Internal Server Error";
      $this->response(
        array('error' => $strErrorDesc), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  public function update() {
    try {
      $this->getUserAuth();
      $bodyParams = $this->getBodyParams();
      $userModel = new UserModel();

      $userModel->updateUser(
        $this->user['id'],
        $bodyParams['first_name'],
        $bodyParams['last_name']
      );

      $this->response(
        array(
          'id' => $this->user['id'],
          'first_name' => $bodyParams['first_name'],
          'last_name' => $bodyParams['last_name']
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
}