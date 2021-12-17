<?php
class ImageBookController extends BaseController {
  public function create() {
    try {
      $queryParams = $this->getQueryParams();
      $bookModel = new BookModel();

      $this->requiredKeys(array('book_id'), $queryParams);

      $book = $bookModel->findBook(
        $queryParams['book_id']
      );

      if (empty($book)) {
        $this->response(
          array("error" => "Livro não encontrado"),
          array('Content-Type: application/json', "{$this->request->serverProtocol} 400 Bad Request")
        );
      }

      $fileError = $this->validateFile('file');

      if(empty($fileError)) {
        $image = $this->getFileName('file');

        $bookModel->updateBookImage($book['id'], $image);

        $this->response(
          array(
            "id" => $book['id'],
            "image" => $image,
          ),
          array('Content-Type: application/json', "{$this->request->serverProtocol} 400 Bad Request")
        );
      } else {
        $this->response(
          array("error" => $fileError),
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

  private function getFilePath($key) {
    return IMAGE_PATH . basename($_FILES["$key"]["name"]);
  }
  
  private function isFileSizeValid($key) {
    if ($_FILES["$key"]["size"] > 1000000)
    return "Tamanho máximo de arquivo permitido é de 1MB";
    return "";
  }
  
  private function isFileTypeValid($key) {
    $imageFileType = strtolower(pathinfo($this->getFilePath($key), PATHINFO_EXTENSION));

    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png")
      return "Apenas arquivos JPG e PNG são permitidos";

    return "";
  }

  private function uploadFile($key) {
    $status = move_uploaded_file($_FILES["$key"]["tmp_name"], $this->getFilePath($key));

    if (!$status)
      return "Erro ao salvar o arquivo";

    return "";
  }
  
  private function validateFile($key) {
    $uploadOk = 1;

    $fileError = "";

    $fileError = $this->isFileSizeValid($key);

    if (empty($fileError))
      $fileError = $this->isFileTypeValid($key);

    if(empty($fileError))
      $fileError = $this->uploadFile($key);

    return $fileError;
  }

  private function getFileName($key) {
    return basename($_FILES["$key"]["name"]);
  }
}