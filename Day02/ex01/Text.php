<?php

class Text{

    private $tab = null;

    function __construct($str){
        $this->tab = $str;
    }

    function append(...$str){
        array_push($this->tab, ...$str);
    }

    function readData(){
        $content = null;
        foreach($this->tab as $element){
            $content .= "<p>" . $element . "</p>\n";
        }
        return $content;
    }
}

?>