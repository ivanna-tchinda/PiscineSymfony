<?php

include "MyException.php";

class Elem{
  public $element;
  public $content;
  public $attributes;

  function __construct($element, $content="", $attributes=""){
    $auth_elems = ["meta", "img", "hr", "br", "html", "head",
    "body", "title", "h1", "h2", "h3", "h4", "h5", "h6", "p", "span", "div",
    "table", "tr", "th", "td", "ul", "ol", "li"];

    $this->content = $content;
    $this->element = $element;
    $this->attributes = $attributes;

    if (in_array($element, $auth_elems)) {
      $this->element = $element;
    } else {
      throw new MyException('Exception raised: Invalid HTML element');
    }
  }

  function pushElement(Elem $elm){
    $this->content .= $elm->getHTML();
  }

  function getHTML(){
    $attr = "";
    $attr_key = "";
    $attr_val = "";

    if($this->attributes){
      foreach($this->attributes as $key => $value){
        $attr_key = $key;
        $attr_val = $value;
        $attr .= $attr_key . "=" . $attr_val . " ";
      }
    }
    

    if($this->element == "meta" || $this->element == "img" || $this->element == "hr" || $this->element == "br")
      return "<$this->element $attr/>\n$this->content\n";
    return "<$this->element  $attr>\n$this->content\n</$this->element>\n";
  }

}

?>