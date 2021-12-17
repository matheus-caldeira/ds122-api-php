<?php
class UserBooksController extends BaseController {
  public function index() {
    try {
      $this->getUserAuth();
      $arrQueryStringParams = $this->getQueryParams();
      $userBookModel = new UserBookModel();

      $intLimit = 10;
      if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
        $intLimit = $arrQueryStringParams['limit'];
      }

      $offset = 0;
      if (isset($arrQueryStringParams['offset']) && $arrQueryStringParams['offset']) {
        $offset = $arrQueryStringParams['offset'];
      }

      $arrUserBooks = $userBookModel->getUserBooks(
        $this->user['id'],
        $intLimit,
        $offset
      );

      $this->response(
        $arrUserBooks,
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
      $this->getUserAuth();
      $bodyParams = $this->getBodyParams();
      $userBookModel = new UserBookModel();

      $this->requiredKeys(array('book_id'), $bodyParams);

      $checkUserBook = $userBookModel->findUserBookByBook(
        $this->user['id'],
        $bodyParams['book_id']
      );

      if (empty($checkUserBook)) {
        $userBook = $userBookModel->createUserBook(
          $this->user['id'],
          $bodyParams['book_id']
        );
        
        $this->response(
          array(
            'id' => $userBook,
            'user_id' => $this->user['id'],
            'book_id' => $bodyParams['book_id']
          ),
          array('Content-Type: application/json', "{$this->request->serverProtocol} 200 OK")
        );
      } else {
        $this->response(
          array('error' => 'Livro já está vinculado'),
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

  public function delete() {
    try {
      $this->getUserAuth();
      $queryParams = $this->getQueryParams();
      $userBookModel = new UserBookModel();

      $this->requiredKeys(array('id'), $queryParams);

      $checkUserBook = $userBookModel->findUserBookByBook(
        $this->user['id'],
        $queryParams['id']
      );

      if (empty($checkUserBook)) {
        $this->response(
          array('error' => 'Livro não vinculado'),
          array('Content-Type: application/json', "{$this->request->serverProtocol} 400 Bad Request")
        );
      } else {
        $userBookModel->deleteUserBook(
          $this->user['id'],
          $queryParams['id']
        );
        
        $this->response(
          array(),
          array('Content-Type: application/json', "{$this->request->serverProtocol} 204 No Content")
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
}