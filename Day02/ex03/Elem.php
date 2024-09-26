<?php

class Elem{
  public $element;
  public $content;

  function __construct($element, $content=""){
    $this->content = $content;
    $this->element = $element;
  }

  function pushElement(Elem $elm){
    $this->content .= $elm->getHTML();
  }

  function getHTML(){
    if($this->element == "meta" || $this->element == "img" || $this->element == "hr" || $this->element == "br")
      return "<" . $this->element . "/>\n" . $this->content . "\n";
    return "<" . $this->element . ">\n" . $this->content . "\n</" . $this->element . ">\n";
  }

}

?>