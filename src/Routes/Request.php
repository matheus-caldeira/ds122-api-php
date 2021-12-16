<?php
  class Request {
    function __construct() {
      $this->bootstrapSelf();
    }

    private function bootstrapSelf() {
      foreach($_SERVER as $key => $value) {
        $this->{$this->toCamelCase($key)} = $value;
      }
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
  }
?>