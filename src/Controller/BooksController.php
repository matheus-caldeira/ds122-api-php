<?php
class BooksController extends BaseController {
  public function index() {
    try {
      $arrQueryStringParams = $this->getQueryParams();
      $bookModel = new BookModel();

      $intLimit = 10;
      if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
        $intLimit = $arrQueryStringParams['limit'];
      }

      $offset = 0;
      if (isset($arrQueryStringParams['offset']) && $arrQueryStringParams['offset']) {
        $offset = $arrQueryStringParams['offset'];
      }

      $arrBooks = $bookModel->getBooks($intLimit, $offset);
      $this->response(
        $arrBooks,
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
      $bookModel = new BookModel();

      $this->requiredKeys(array('title', 'description'), $bodyParams);

      $book = $bookModel->createBook(
        $bodyParams['title'],
        $bodyParams['description']
      );

      $this->response(
        array(
          'id' => $book,
          'title' => $bodyParams['title'],
          'description' => $bodyParams['description'],
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

  public function update() {
    try {
      $bodyParams = $this->getBodyParams();
      $bookModel = new BookModel();

      $this->requiredKeys(array('id', 'title', 'description'), $bodyParams);


      $bookModel->updateBook(
        $bodyParams['id'],
        $bodyParams['title'],
        $bodyParams['description']
      );

      $this->response(
        array(
          'id' => $bodyParams['id'],
          'title' => $bodyParams['title'],
          'description' => $bodyParams['description']
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