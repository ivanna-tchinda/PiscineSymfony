<?php

class Coffee extends HotBeverage{
    private string $description = "A black coffee.";
    private string $comment = "Tasty.";

    function __constructor(){
        $this->name = "Coffee";
    }

    function getDescription(){
        return $this->description;
    }

    function getComment(){
        return $this->comment;
    }
}

?>