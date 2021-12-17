<?php
  class Request {
    function __construct() {
      $this->bootstrapSelf();
    }

    private function bootstrapSelf() {
      foreach($_SERVER as $key => $value) {
        $this->{$this->toCamelCase($key)} = $value;
      }
      $this->headers = apache_request_headers();
      $this->uri = parse_url($this->requestUri, PHP_URL_PATH);
    }

    private function toCamelCase($string) {
      $result = strtolower($string);
          
      preg_match_all('/_[a-z]/', $result, $matches);

      foreach($matches[0] as $match) {
        $c = str_replace('_', '', strtoupper($match));
        $result = str_replace($match, $c, $result);
      }

      return $result;
    }

    private function getRequestHeaders() {
      $headers = array();
      foreach($_SERVER as $key => $value) {
          if (substr($key, 0, 5) <> 'HTTP_') {
              continue;
          }
          $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
          $headers[$header] = $value;
      }
      return $headers;
  }
  }
?>