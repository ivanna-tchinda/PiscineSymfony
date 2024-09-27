<?php


class TemplateEngine{
  private $elm;

  function __construct(Elem $elm){
    $this->elm = $elm;
  }

  function createFile($fileName){
    file_put_contents($fileName, $this->elm->getHTML(), FILE_APPEND);
  }
}

?>