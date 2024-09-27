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
  
  public function validPage($htmlFile) {
    // Lire le contenu du fichier HTML
    $content = file_get_contents($htmlFile);

    // Règles de validation sous forme d'expressions régulières
    $rules = [
        // La balise parent doit être <html> avec un seul <head> et <body> dans cet ordre
        'html' => '/<html>.*<head>.*<\/head>.*<body>.*<\/body>.*<\/html>/is',
        // L'élément <head> doit contenir un <title> et un <meta charset>
        'head' => '/<head>.*<title>.*<\/title>.*<meta[^>]*charset[^>]*>.*<\/head>/is',
        // Les balises <p> peuvent contenir du texte et d'autres balises
        'p' => '/<p>(.*?)<\/p>/is',
        // La balise <table> ne doit contenir que des <tr> et les <tr> ne doivent contenir que des <th> ou <td>
        'table' => '/<table>(?:\s*<tr>(?:\s*<t[hd]>[^<]*<\/t[hd]>\s*)+<\/tr>\s*)+<\/table>/is',
        // Les balises <ul> et <ol> ne doivent contenir que des <li>
        'ul_ol' => '/<(ul|ol)>(?:\s*<li>[^<]*<\/li>\s*)+<\/(ul|ol)>/is',
    ];

    // Vérifier si le contenu correspond aux règles
    foreach ($rules as $tag => $regex) {
        if (!preg_match($regex, $content)) {
            echo "false $tag\n";
            return false;
        }
    }

    echo "true\n";
    return true;
}


}

?>