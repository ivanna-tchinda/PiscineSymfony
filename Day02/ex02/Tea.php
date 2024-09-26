<?php

class Tea extends HotBeverage{
    private $description = "A green tea.";
    private $comment = "Very good.";

    function __constructor(){
        $this->name = "Tea";
    }

    function getDescription(){
        return $this->description;
    }

    function getComment(){
        return $this->comment;
    }

    
}

?>